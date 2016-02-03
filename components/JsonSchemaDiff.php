<?php
/**
 * Created by PhpStorm.
 * User: alekseylaptev
 * Date: 20.03.15
 * Time: 12:15
 */

namespace app\components;


use mikemccabe\JsonPatch\JsonPatch;

class JsonSchemaDiff
{

    private static $src;

    private static $dst;

    public static function diff($src, $dst)
    {

        self::$src = $src;
        self::$dst = $dst;

        $result = [];

        $rows = JsonPatch::diff($src, $dst);

        foreach ($rows as $key => $row) {
            $root = self::getRootName($row['path']);
            if (!isset($result[$root])) {
                $result[$root] = [];
            }

            switch ($root) {
                case 'objects':
                    $result[$root] = AppSettings::mergeArray($result[$root], self::getObjectDiff($row, $key));
                    unset($rows[$key]);
                    break;
                case 'methods':
                    $result[$root] = AppSettings::mergeArray($result[$root], self::getMethodDiff($row));
                    break;
            }
        }

        return $result;
    }

    private static function getRootName($str)
    {
        $str = trim($str, '/');
        $chunks = explode('/', $str);
        $root = $chunks[0];

        if (!in_array($root, ['stat', 'objects', 'methods'])) {
            throw new \Exception('Invalid root: ' . $root);
        }
        return $root;
    }

    private static function getMethodDiff($row)
    {
        $result = [];
        $path = substr($row['path'], strlen('/methods/'));
        $chunks = explode('/', $path);
        $controller = $chunks[0];

        $tree = self::strToTree($path);

        if (isset($tree[$controller]['actions'])) {
            $action = $chunks[2];
            if (isset($tree[$controller]['actions'][$action]['response'])) {
                $propIndex = 5;
                $property = $chunks[$propIndex];
                $newValue = isset($row['value']) ? $row['value'] : null;

                $result[$controller]['actions'][$action]['properties'][$property] = [
                    'op' => $row['op'],
                    'path' => implode('/', array_slice($chunks, $propIndex + 1)),
                    'new_value' => is_array($newValue) ? json_encode($newValue) : $newValue ,
                    'prev_value' => self::getPrevMethodAttr('/methods/' . $path)
                ];
            }
            else {
//                throw new \Exception('Section "response" not found');
            }
        }

        return $result;
    }

    private static function getObjectDiff($row, $key)
    {
        $result = [];
        $path = substr($row['path'], strlen('/objects/'));
        $chunks = explode('/', $path);
        $object = $chunks[0];

        if (isset($chunks[1]) && isset($chunks[2])) {
            $section = $chunks[1];
            $property = $chunks[2];
            $value = isset($row['value']) ? $row['value'] : null;
            switch ($section) {
                case 'properties':
                    $path = implode('/', array_slice($chunks, 3));
                    $path = preg_replace('|/\d+|', '[]', $path);
                    $result[$object][$section][$property][md5($path)] = [
                        'op' => $row['op'],
                        'path' => $path,
                        'new_value' => self::getCurrentObjectPropertyValue($object, $property, $path, $value),
                        'prev_value' => self::getPrevObjectPropertyValue($object, $property, $path)
                    ];
                    break;
                case 'required';
                    $result[$object][$section] = [
                        'op' => $row['op'],
                        'path' => implode('/', array_slice($chunks, 3)),
                        'new_value' => self::getCurrentObjectAttr($object, $section, $value),
                        'prev_value' => self::getPrevObjectAttr($object, $section)
                    ];
                    break;
                case 'meta':
                    break;
                default:
                    throw new \Exception('"' . $section . '" not processed in object');
            }
        } else {
            $result[$object]['diff'] = [
                'op' => $row['op'],
                'path' => null,
                'new_value' => isset($row['value']) ? $row['value'] : null,
                'prev_value' => null,
                'comment' => 'Новый объект'
            ];
        }
        return $result;
    }

    private static function strToTree($str)
    {
        $keyChunks = [$str => $str];
        $result = self::explodeTree($keyChunks, '/');
        return $result;
    }

    private static function explodeTree($array, $delimiter = "_", $baseval = false)
    {
        if (!is_array($array)) return false;
        $splitRE = "/" . preg_quote($delimiter, "/") . "/";
        $returnArr = array();
        foreach ($array as $key => $val) {
            // Get parent parts and the current leaf
            $parts = preg_split($splitRE, $key, -1, PREG_SPLIT_NO_EMPTY);
            $leafPart = array_pop($parts);

            // Build parent structure
            // Might be slow for really deep and large structures
            $parentArr = &$returnArr;
            foreach ($parts as $part) {
                if (!isset($parentArr[$part])) {
                    $parentArr[$part] = array();
                } elseif (!is_array($parentArr[$part])) {
                    if ($baseval) {
                        $parentArr[$part] = array("__base_val" => $parentArr[$part]);
                    } else {
                        $parentArr[$part] = array();
                    }
                }
                $parentArr = &$parentArr[$part];
            }

            // Add the final part to the structure
            if (empty($parentArr[$leafPart])) {
                $parentArr[$leafPart] = $val;
            } elseif ($baseval && is_array($parentArr[$leafPart])) {
                $parentArr[$leafPart]["__base_val"] = $val;
            }
        }
        return $returnArr;
    }

    private static function getPrevMethodAttr($path)
    {
        try{
            $result = JsonPatch::get(self::$src, $path);
        }
        catch(\Exception $e){
            $result = null;
        }
        return is_array($result) ? json_encode($result) : $result;
    }

    private static function getPrevObjectAttr($objectId, $attrId)
    {
        return self::getObjectAttr(self::$src['objects'], $objectId, $attrId);
    }

    private static function getCurrentObjectAttr($objectId, $attrId, $value)
    {
        return self::getObjectAttr(self::$dst['objects'], $objectId, $attrId, $value);
    }

    private static function getObjectAttr($objects, $objectId, $attrId, $value = null)
    {
        $result = isset($objects[$objectId][$attrId]) ? $objects[$objectId][$attrId] : '<code>not found</code>';

        if (is_array($result)) {
            $result = json_encode($result);
            $result = str_replace(',', ', ', $result);
        }

        return $result;
    }


    private static function getPrevObjectPropertyValue($objectId, $propertyId, $path)
    {
        return self::getObjectPropertyValue(self::$src['objects'], $objectId, $propertyId, $path);
    }

    private static function getCurrentObjectPropertyValue($objectId, $propertyId, $path, $value)
    {
        return self::getObjectPropertyValue(self::$dst['objects'], $objectId, $propertyId, $path, $value);
    }

    private static function getObjectPropertyValue($objects, $objectId, $propertyId, $path, $value = null)
    {
        $attr = explode('[', $path)[0];
        $result = isset($objects[$objectId]['properties'][$propertyId][$attr]) ? $objects[$objectId]['properties'][$propertyId][$attr] : '<code>not found</code>';
        if (is_array($result)) {
            if (isset($result[0]) && $result[0] == null) {
                $result[0] = 'null';
            }
            $result = json_encode($result);
            $result = str_replace(',', ', ', $result);
        }
        return $result;
    }


}