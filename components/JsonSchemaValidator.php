<?php


namespace app\components;


use app\helpers\ParamsHelper;
use app\models\Options;
use app\models\Sources;
use Yii;
use Exception;
use app\helpers\JsonHelper;
use app\models\JsonHistory;

class JsonSchemaValidator
{

    const JSON_TYPE_RESPONSE = 'response';

    const JSON_TYPE_REQUEST = 'request';

    private $version;

    /**
     * @var \yii\caching\FileCache
     */
    private $cache;

    public function __construct($version = null)
    {
        $this->setVersion($version);
    }

    public function validateRequest($inputData)
    {

    }

    public function saveToDb()
    {
        $content = $this->getDocData();
        $hash = md5($content);
        $version = $this->getVersion();

        $model = JsonHistory::find()->where(['hash' => $hash, 'version' => $version])->one();
        if (!$model) {
            $model = new JsonHistory();
            $model->content = $content;
            $model->hash = $hash;
            $model->version = $version;
            $model->created_at = time();
            $model->size = strlen($content);
            if (!$model->save()) {
                var_dump($model->errors);
                die(__METHOD__);
            }
        }
    }

    /**
     * Вся документация в одном json
     * @return string
     * @throws Exception
     */
    public function getDocData()
    {
//        $this->buildDefinitions();
        $methods = $this->getMethods();
        $objects = $this->getObjects();
        $result = [
            'stat' => $this->getStat($methods, $objects),
            'methods' => $methods,
            'objects' => $objects
        ];

        $content = json_encode($result);

        $this->checkDefinitions($content, $objects);

        return $content;
    }

    /**
     * Проверка кривых ссылок на объекты
     * @param $content
     * @param $objects
     * @throws Exception
     */
    private function checkDefinitions($content, $objects)
    {
        $definitions = [];
        $pattern = "|\\\/definitions\\\/[A-Za-z0-1А-Яа-я]+|im";
        preg_match_all($pattern, $content, $matches);
        if (isset($matches[0])) {
            $definitions = $matches[0];
        }
        $definitions = array_unique($definitions);
        foreach ($definitions as $str) {
            $chunks = array_reverse(explode('\/', $str));
            if (!isset($objects[$chunks[0]])) {
                throw new Exception('Object "' . $chunks[0] . '" from definition link not found in objects list');
            }
        }
    }

    /**
     * Статистика о доке
     * @param $methods
     * @param $objects
     * @return array
     */
    private function getStat($methods, $objects)
    {
        $actions_count = 0;
        foreach ($methods as $method) {
            $actions_count += count($method['actions']);
        }

        $result = [
            'objects_count' => count($objects),
            'controllers_count' => count($methods),
            'actions_count' => $actions_count,
            'version' => $this->getVersion(),
            'versions' => $this->getAvailableVersions()
        ];
        return $result;
    }

    /**
     * Список доступных версий
     * @return array
     */
    public function getAvailableVersions()
    {
        $files = scandir($this->getDocDir());
        $result = [];
        foreach ($files as $file) {
            if ($file[0] == 'v') {
                $result[] = (int)substr($file, 1);
            }
        }
        $result = array_intersect($result, ParamsHelper::getAllowedVersions());
        return $result;
    }

    /**
     * @return array
     * @throws AppException
     * @throws Exception
     */
    private function getMethods()
    {
        $saveVersion = ParamsHelper::getVersion();
        Yii::$app->params['version'] = $this->getVersion();

        $dir = $this->getMethodsDir();
        $files = $this->dirToArray($dir);
        $result = [];

        foreach ($files as $methodDir => $methodFiles) {
            if (is_array($methodFiles)) {
                $result[$methodDir]['meta'] = null;
                $result[$methodDir]['actions'] = [];
                foreach ($methodFiles as $methodFile) {
                    if (pathinfo($methodFile)['extension'] == 'json') {
                        $file = $dir . '/' . $methodDir . '/' . $methodFile;
                        $filename = pathinfo($methodFile)['filename'];
                        $md = "{$dir}/{$methodDir}/{$filename}.md'";
                        $content = JsonHelper::decode(file_get_contents($file));


                        if (isset($content['request']['properties'])) {
                            foreach ($content['request']['properties'] as $key => $value) {
                                $content['request']['properties'][$key]['id'] = $key;
                            }
                        }

                        if ($content) {

                            if (in_array(basename($md), $methodFiles)) {
                                $content['meta']['md'] = file_get_contents($md);
                            }

                            if ($methodFile == 'meta.json') {
                                $result[$methodDir]['meta']['id'] = $methodDir;
                                $result[$methodDir]['meta'] = array_merge($result[$methodDir]['meta'], $content);
                            } else {
                                $result[$methodDir]['actions'][$filename] = $content;
                            }
                        } else {
                            throw new Exception('Invalid json in ' . $file);
                        }

                        if ($filename !== 'meta') {
                            if (isset($content['request']['links'])) {
                                throw new Exception("Links should not be set for controller {$methodDir} and action {$filename}");
                            }
                            $oldMethod = isset($content['request']['method']) ? $content['request']['method'] : null;
                            list($path, $method) = $this->getLinkParams($methodDir, $filename, null);
                            if ($method && $oldMethod) {
                                throw new Exception("Request method {$oldMethod} should not be set for controller {$methodDir} and action {$filename}");
                            }
                            if (!$method) {
                                $method = $oldMethod ?: 'POST';
                            }
                            $result[$methodDir]['actions'][$filename]['request']['method'] = $method;
                            $result[$methodDir]['actions'][$filename]['request']['links'] = [['href' => $path]];

//                            $relations = $this->getRelations($methodDir, $filename, $content);
//                            $relationsJson = str_replace('[]', '{}', JsonHelper::encode($relations));
//                            $result[$methodDir]['actions'][$filename]['request']['properties']['relations'] = [
//                                'default' => $relationsJson,
//                                'id' => 'relations',
//                                'type' => 'string',
//                            ];
                        }
                    }
                }
            }
        }
        Yii::$app->params['version'] = $saveVersion;
        return $result;
    }

    private function getLinkParams($controller, $action)
    {
        switch ($action) {
            case 'add':
                return [$controller, 'POST'];
            case 'list':
                return [$controller, 'GET'];
            case 'details':
                return [$controller . '/{id}', 'GET'];
            case 'edit':
                return [$controller . '/{id}', 'PUT'];
            case 'delete':
                return [$controller . '/{id}', 'DELETE'];
            default:
                return ["{$controller}/{$action}", null];
        }
    }

    /**
     * Массив объектов
     * @return array
     * @throws Exception
     */
    private function getObjects()
    {
        $result = [];
        $dirs = [$this->getObjectsDir(), $this->getCommonDir()];

        foreach ($dirs as $dir) {
            $this->parseObjectsInDirRecursive($dir, $result);
        }
        return $result;
    }

    /**
     * @param $dir
     * @param $result
     * @throws Exception
     */
    private function parseObjectsInDirRecursive($dir, &$result)
    {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }

            $filepath = $dir . '/' . $file;
            if (is_dir($filepath)) {
                $this->parseObjectsInDirRecursive($filepath, $result);
            } else if (pathinfo($file)['extension'] == 'json') {
                $object = json_decode(file_get_contents($filepath), 1);
                if (!$object) {
                    throw new Exception('Invalid json in ' . $filepath);
                }
                if (!isset($result[$object['id']])) {
                    foreach ($object['properties'] as $id => $property) {
                        $object['properties'][$id]['id'] = $id;
                    }

                    $result[$object['id']] = $object;
                } else {
                    throw new Exception('Key "' . $object['id'] . '" already exists in definitions');
                }

                switch ($object['id']) {
                    case 'notification': {
//                        $result[$object['id']]['properties']['type']['enum'] = Yii::$app->rNotification->getAllowedTypes();
                    }
                }
            }
        }
    }


    public function setVersion($value)
    {
        $this->version = (int)$value;
    }


    /**
     * @return mixed
     */
    private function getVersion()
    {
        return $this->version ? $this->version : ParamsHelper::getVersion();
    }

    private function getSchemaPath($controller, $action)
    {
        $chunks = explode('-', $controller);

        $str = '';
        foreach ($chunks as $key => $chunk) {
            if ($key) {
                $chunk = ucfirst($chunk);
            }
            $str .= $chunk;
        }

//        $str = $controller;

        return $this->getVersionDir() . '/methods/' . $str . '/' . $action . '.json';
    }

    private function getMethodsDir()
    {
        return $this->getVersionDir() . '/methods';
    }

    public function getVersionDir($check = true)
    {
        $dir = $this->getDocDir() . '/v' . $this->getVersion();

        if (!file_exists($dir)) {
            if ($check) {
                throw new Exception('Docs for version ' . $this->getVersion() . ' not found in ' . $dir);
            } else {
                return false;
            }
        }

        return $dir;
    }

    private function getDocDir()
    {
        return Sources::getCurrentSource();
    }

    private function getObjectsDir()
    {
        return $this->getVersionDir() . '/objects';
    }

    private function getCommonDir()
    {
        return $this->getVersionDir() . '/common';
    }

    private function getDefinitions()
    {
        return $this->getVersionDir() . '/definitions.json';
    }

    /**
     * Склейка всех объектов в один файл definitions
     * @throws Exception
     */
    public function buildDefinitions()
    {
        $saveVersion = ParamsHelper::getVersion();
        Yii::$app->params['version'] = $this->getVersion();
        $definitions = $this->getDefinitions();
        $objects = $this->getObjects();
        $data = [
            'definitions' => $objects
        ];
        $content = json_encode($data);

        file_put_contents($definitions, $content);
        @chmod($definitions, 0777);

        Yii::$app->params['version'] = $saveVersion;
    }

    private function dirToArray($dir)
    {

        $result = array();

        $cdir = scandir($dir);
        foreach ($cdir as $key => $value) {
            if (!in_array($value, array(".", ".."))) {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                    $result[$value] = $this->dirToArray($dir . DIRECTORY_SEPARATOR . $value);
                } else {
                    $result[] = $value;
                }
            }
        }

        return $result;
    }
}