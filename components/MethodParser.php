<?php
/**
 * Created by PhpStorm.
 * User: alekseylaptev
 * Date: 18.02.15
 * Time: 13:38
 */

namespace app\components;


use yii\helpers\Url;

class MethodParser
{

    private $data;

    public function load($data)
    {
        $this->data = $data;
    }

    public function getId()
    {
        return $this->data['meta']['id'];
    }

    public function getTitle()
    {
        return $this->data['meta']['title'];
    }

    public function getDescription()
    {
        return $this->data['meta']['description'];
    }

    public function getActions()
    {
        $actions = [];

        foreach ($this->data['actions'] as $actionId => $action) {
            $item = $action;
            $item['url'] = Url::toRoute([
                    'methods/view',
                    'controller' => $this->data['meta']['id'],
                    'action' => $actionId
                ]
            );
            $item['id'] = $actionId;
            $actions[] = $item;
        }

        return $actions;
    }

    public function selected($action)
    {
        return \Yii::$app->controller->id == 'methods'
        && \Yii::$app->request->getQueryParam('controller') == $this->data['meta']['id']
        && \Yii::$app->request->getQueryParam('action') == $action['id'];
    }
}