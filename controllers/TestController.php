<?php

namespace app\controllers;

use app\components\SecuredModuleController;
use yii\rest\Controller;
use yii\rest\ViewAction;

class TestController extends SecuredModuleController
{
    public function actionCheckList()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        \Yii::$app->response->headers->add('Content-type', 'application/json; charset=utf-8');
        $data = \Yii::$app->docParser->load()->getCheckList();
        echo json_encode($data);
        \Yii::$app->end();
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

}
