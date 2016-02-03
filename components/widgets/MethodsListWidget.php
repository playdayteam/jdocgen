<?php
/**
 * Created by PhpStorm.
 * User: alekseylaptev
 * Date: 09.12.15
 * Time: 14:03
 */

namespace app\components\widgets;


use yii\bootstrap\Widget;

class MethodsListWidget extends Widget
{

    public function run()
    {
        $data = \Yii::$app->docParser->getContent();
        $methods = $data['methods'];
        return $this->render('methodsList', ['methods' => $methods]);
    }

}