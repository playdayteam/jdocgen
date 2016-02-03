<?php
/**
 * Created by PhpStorm.
 * User: alekseylaptev
 * Date: 18.02.15
 * Time: 13:30
 */

namespace app\components;


use yii\helpers\Url;

class ObjectParser
{

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getId()
    {
        return $this->data['id'];
    }

    public function getTitle()
    {
        return isset($this->data['title']) && $this->data['title'] ? $this->data['title'] : ucfirst($this->data['id']);
    }

    public function getChildren($objects)
    {
        $result = [];

        foreach ($objects as $object) {
            if (isset($object['meta']['parent'])) {
                if ($object['meta']['parent'] == $this->getId()) {
                    $result[] = $object;
                }
            }
        }

        return $result;
    }

    public function isRoot()
    {
        return !isset($this->data['meta']['parent']);
    }

    public function getDescription()
    {
        return isset($this->data['description']) ? $this->data['description'] : 'description не найден';
    }


    public function getUrl()
    {
        return Url::toRoute([
            'objects/view',
            'id' => $this->data['id']
        ]);
    }

    public function selected()
    {
        return \Yii::$app->controller->id == 'objects' && \Yii::$app->request->getQueryParam('id') == $this->data['id'];
    }

}