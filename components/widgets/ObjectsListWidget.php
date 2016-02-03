<?php
/**
 * Created by PhpStorm.
 * User: alekseylaptev
 * Date: 09.12.15
 * Time: 18:04
 */

namespace app\components\widgets;


use yii\base\Widget;

class ObjectsListWidget extends Widget
{

    public function run()
    {
        $data = \Yii::$app->docParser->getContent();
        $objects = $data['objects'];
        return $this->render('objectsList', ['objects' => $objects]);
    }

}