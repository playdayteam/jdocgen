<?php
/**
 * Created by PhpStorm.
 * User: alekseylaptev
 * Date: 18.02.15
 * Time: 13:16
 */

namespace app\components\widgets;

use yii\bootstrap\Widget;
use Yii;

class ObjectsMenuWidget extends Widget
{

    public function Run()
    {
        Yii::$app->docParser->load();
        $objects = Yii::$app->docParser->getObjects();
        echo $this->render('objectsMenuWidget', ['objects' => $objects]);
    }

}