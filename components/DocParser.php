<?php

namespace app\components;

use app\components\JsonSchemaValidator;
use mikemccabe\JsonPatch\JsonPatch;
use Yii;
use yii\base\Component;
use yii\helpers\Url;

class DocParser extends Component
{

    public $sourceUrl;

    private $rawContent;

    private $cache;

    public function load($version = null)
    {
        $version = $version ? $version : $this->getVersion();
        if (!isset($this->cache[$version]['data'])) {
            $validator = new JsonSchemaValidator($version);
            if (!$validator->getVersionDir(false)) {
                $this->setVersion(0);
                $validator = new JsonSchemaValidator(null);
            }
            $this->cache[$version]['data'] = json_decode($validator->getDocData(), 1);
            $this->cache[$version]['timestamp'] = time();
        }
        $this->rawContent = $this->cache[$version]['data'];
        return $this;
    }

    public static function sendRequest($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch), 1);
        $info = curl_getinfo($ch);
        $code = $info['http_code'];
        return [$response, $code];
    }

    public function getContent()
    {
        return $this->rawContent;
    }

    public function getObjects()
    {
        return $this->rawContent['objects'];
    }

    public function getMethods()
    {
        return $this->rawContent['methods'];
    }

    public function refresh()
    {
        $params = ['sourceUrl', 'version'];
        foreach ($params as $param) {
            setcookie($param, null, time() - 3600 * 24 * 365, '/');
        }
    }

    public function setVersion($value)
    {
        setcookie('version', $value, time() + 3600 * 24 * 365, '/');
    }

    public function getVersion()
    {
        return isset($_COOKIE['version']) && $_COOKIE['version'] ? $_COOKIE['version'] : $this->rawContent['stat']['version'];
    }

    public function getVersions()
    {
        return $this->rawContent['stat']['versions'];
    }

    public function getObject($id)
    {
        return isset($this->rawContent['objects'][$id]) ? $this->rawContent['objects'][$id] : null;
    }

    public function getOneOfObject($parentObjectId, $property, $oneOfId)
    {
        $parentObject = $this->getObject($parentObjectId);
        if ($parentObject) {
            foreach ($parentObject['properties'][$property]['oneOf'] as $key => $object) {
                if ($object['type'] == 'object' && $object['id'] == $oneOfId) {
                    return $object;
                }
            }
            return null;
        } else {
            return null;
        }
    }

    public function getMethod($controller, $action)
    {
        return isset($this->rawContent['methods'][$controller]['actions'][$action]) ? $this->rawContent['methods'][$controller]['actions'][$action] : null;
    }

    public function getControllerMeta($controller)
    {
        return $this->rawContent['methods'][$controller]['meta'];
    }

    public function getOneOfMethod($controller, $action, $property, $oneOfId)
    {
        $method = $this->getMethod($controller, $action);

        if ($method) {
            foreach ($method['request']['properties'][$property]['oneOf'] as $key => $object) {
                if ($object['type'] == 'object' && $object['id'] == $oneOfId) {
                    return $object;
                }
            }
            return null;
        } else {
            return null;
        }
    }

    public function getCheckList()
    {
        $result = [
            'methods' => [],
            'objects' => []
        ];

        foreach ($this->rawContent['methods'] as $controller => $data) {
            foreach ($data['actions'] as $actionId => $actionData) {
                $result['methods'][] = '/methods/view?controller=' . $controller . '&action=' . $actionId;
            }
        }

        foreach ($this->rawContent['objects'] as $object) {
            $result['objects'][] = Url::to(['objects/' . $object['id']]);
        }

        return $result;
    }

    public function getDiff()
    {
        $versions = $this->load()->getVersions();

        $data = [];
        foreach ($versions as $version) {
            $data[$version] = $this->load($version)->getContent();
        }

        return JsonSchemaDiff::diff($data[8], $data[9]);
    }
}