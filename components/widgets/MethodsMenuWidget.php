<?php

namespace app\components\widgets;

use yii\bootstrap\Widget;

class MethodsMenuWidget extends Widget{

    public function Run(){
        \Yii::$app->docParser->load();
        $methods = \Yii::$app->docParser->getMethods();
        echo $this->render('methodsMenuWidget', ['methods' => $methods]);
    }

}