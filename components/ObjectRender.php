<?php
namespace app\components;

use app\components\widgets\ObjectAttrTableWidget;
use yii\base\Exception;
use yii\helpers\Html;
use yii\helpers\Url;

class ObjectRender
{

    private static $hasNull = false;

    public static function renderNull($data)
    {
        if (isset($data['enum']) && is_array($data['enum'])) {
            $value = in_array(null, $data['enum']);
        } else {
            if (isset($data['type']) && (is_array($data['type']) && in_array('null', $data['type']) || $data['type'] == 'null') || self::$hasNull) {
                $value = true;
            } else {
                $value = false;
            }
        }

        self::$hasNull = false;
        return self::renderBool($value);
    }

    public static function renderDescription($data, $id = null)
    {
        if ($id) {
            $data['id'] = $id;
        }
        $result = null;
        $type = isset($data['type']) ? $data['type'] : null;
        if (isset($data['description'])) {
            $result = $data['description'];
            $result .= '<br>';
        }

        if (isset($data['$ref'])) {
            $refClass = self::getObjectFromRef($data['$ref']);
            $result .= 'An object of class ' . Html::a(ucfirst($refClass), Url::toRoute(['objects/' . $refClass]));
            $result .= '<br/>';
        }

        if (isset($data['enum'])) {
            $result .= self::renderEnum($data);
        }

        if ($type == 'array') {

            if (isset($data['items']['$ref'])) {
                $refClass = self::getObjectFromRef($data['items']['$ref']);
                $result .= 'Array of objects ' . Html::a(ucfirst($refClass), Url::toRoute(['objects/' . $refClass]));
                $result .= '<br/>';
            }

            if (isset($data['items']['type']) && $data['items']['type'] == 'object' && isset($data['items']['properties'])) {
                $result .= '<br>';
                $result .= '<strong>Object description:</strong>';
                $result .= ObjectAttrTableWidget::widget(['object' => $data['items']]);

            }

            if (isset($data['items']['oneOf'][0]['$ref'])) {
                $result .= 'An array of objects:';
                $property = isset($data['id']) ? $data['id'] : null;
                $result .= self::renderOneOf($data['items']['oneOf'], $property);
            }
            
            if (isset($data['items']['enum'])) {
                $result .= self::renderEnum($data['items'], 'The array consists of');
            }

        }

        if ($type == 'object' && !empty($data['properties'])) {
            $result .= '<br>';
            $result .= '<strong>:</strong>';
            $result .= ObjectAttrTableWidget::widget(['object' => $data,        'showNull' => false,
        'showRequired' => true]);
        }

        if (isset($data['meta']['html'])) {
            $result .= $data['meta']['html'];
        }

        if (isset($data['oneOf']) && is_array($data['oneOf'])) {
            $result .= self::renderOneOf($data['oneOf'], $data['id']);
        }

        if (isset($data['default'])) {
            $result .= '<strong>Default value: </strong><code>' . $data['default'] . '</code>';
        }

        if (isset($data['meta']['restrictions'])) {
            $result .= '<div class="alert alert-warning" role="alert"><strong>Restrictions</strong><br>' . $data['meta']['restrictions'] . '</div>';
        }


        return $result;
    }
    
    private static function renderEnum($data, $caption = 'Possible values')
    {
        $result = "<strong>{$caption}:</strong>";
        $result .= '<ul>';
        foreach ($data['enum'] as $value) {
            $description = isset($data['meta']['enum'][$value]) ? $data['meta']['enum'][$value] : null;

            if (gettype($value) == 'boolean') {
                $value = $value ? 'true' : 'false';
            }

            if ($value === null) {
                $value = '<strong>null</strong>';
            }

            if ($description) {
                $result .= '<li><code>' . $value . '</code> - ' . $description . '</li>';
            } else {
                $result .= '<li><code>' . $value . '</code></li>';
            }
        }
        $result .= '</ul>';
        $result .= '<br/>';
        return $result;
    }

    private static function renderOneOf($data, $property = null)
    {
        $result = '<ol>';
        foreach ($data as $object) {
            if (isset($object['$ref'])) {
                $refClass = self::getObjectFromRef($object['$ref']);
                $str = Html::a(ucfirst($refClass), Url::toRoute(['objects/' . $refClass]));
            } else {
                if (isset($object['type']) && $object['type'] == 'null') {
                    $str = '<code>null</code>';
                } else {
                    if (is_null($property)) {
                        throw new Exception('You must set the id property if it clearly describes the objects in oneOf');
                    }

                    if (\Yii::$app->request->getQueryParam('id')) {
                        $url = Url::toRoute([
                            'objects/one-of',
                            'object' => \Yii::$app->request->getQueryParam('id'),
                            'property' => $property,
                            'one_of_id' => $object['id']
                        ]);
                    } elseif (\Yii::$app->request->getQueryParam('controller')) {
                        $url = Url::toRoute([
                            'methods/one-of',
                            'controller' => \Yii::$app->request->getQueryParam('controller'),
                            'action' => \Yii::$app->request->getQueryParam('action'),
                            'property' => $property,
                            'one_of_id' => $object['id']
                        ]);
                    }
                    $str = Html::a($object['title'], $url);
                }
            }
            $result .= '<li>' . $str . '</li>';
        }
        $result .= '</ol>';
        return $result;
    }

    public static function renderMethod($method)
    {
        $method = strtolower($method);
        switch ($method) {
            case 'get':
                $class = 'success';
                break;
            case 'post':
                $class = 'warning';
                break;
            case 'delete':
                $class = 'danger';
                break;
            case 'put':
                $class = 'primary';
                break;
            default:
                $class = 'default';
        }
        return '<span class="label label-' . $class . '">' . $method . '</span>';
    }

    public static function renderBool($value)
    {
        if ($value) {
            $class = 'success';
            $label = 'Yes';
        } else {
            $class = 'danger';
            $label = 'No';
        }

        return '<span class="label label-' . $class . '">' . $label . '</span>';
    }

    public static function renderType($data)
    {
        $type = isset($data['type']) ? $data['type'] : null;

        if (!$type) {
            if (isset($data['enum'])) {
                $value = null;
                foreach ($data['enum'] as $k => $v) {
                    if ($v != null) {
                        $value = $v;
                        break;
                    }
                }
                switch (gettype($value)) {
                    case 'integer':
                        $type = 'number';
                        break;
                    case 'double':
                        $type = 'number';
                        break;
                    case 'string':
                        $type = 'string';
                        break;
                    case 'boolean':
                        $type = 'boolean';
                }
            } else {
                if (isset($data['$ref'])) {
                    $type = 'object';
                } else {
                    if (isset($data['oneOf'])) {
                        $type = 'object';
                        foreach ($data['oneOf'] as $key => $value) {
                            if (isset($value['type']) && $value['type'] == 'null') {
                                $type = ['object', 'null'];
                                self::$hasNull = true;
                                break;
                            }
                        }
                    }
                }
            }
        }

        if ($type) {
            if (is_array($type)) {
                foreach ($type as $k => $v) {
                    if ($v == 'null') {
                        unset($type[$k]);
                    }
                }
                $value = implode(', ', $type);
            } else {
                $value = $type;
            }

            $class = self::getTypeClass($value);

            $result = '<span class="label label-' . $class . '">' . $value . '</span>';

            $subtype = null;
            if ($type == 'array') {
                if (isset($data['items']['$ref'])) {
                    $subtype = 'object';
                } else {
                    if (isset($data['items']['type'])) {
                        $subtype = $data['items']['type'];
                    }
                }
                if ($subtype) {
                    $class = self::getTypeClass($subtype);
                    $result .= '<span class="label label-' . $class . '">' . $subtype . '</span>';
                }
            }
        } else {
            $result = '<small>Not set</small>';
        }
        return $result;
    }

    private static function getTypeClass($type)
    {
        self::checkType($type);
        switch ($type) {
            case 'number':
                $class = 'info';
                break;
            case 'string':
                $class = 'primary';
                break;
            case 'array':
                $class = 'warning';
                break;
            default:
                $class = 'default';
        }
        return $class;
    }

    private static function checkType($type)
    {
        $availableTypes = ['number', 'string', 'array', 'object', 'null', 'boolean'];
        if (!in_array($type, $availableTypes)) {
            throw new \Exception('Type must be in [' . implode(', ', $availableTypes) . '], current - ' . $type);
        }
    }

    private static function getObjectFromRef($str)
    {
        return array_reverse(explode('/', $str))[0];
    }
}