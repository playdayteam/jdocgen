<?php

namespace app\controllers;

use app\components\SecuredModuleController;
use yii\web\NotFoundHttpException;

class MethodsController extends SecuredModuleController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionView()
    {
        $controller = \Yii::$app->request->getQueryParam('controller');
        $action = \Yii::$app->request->getQueryParam('action');
        $method = \Yii::$app->docParser->load()->getMethod($controller, $action);
        $controllerMeta = \Yii::$app->docParser->load()->getControllerMeta($controller);

        if (!$method) {
            throw new NotFoundHttpException();
        }
        return $this->render('view', [
            'method' => $method,
            'controllerMeta' => $controllerMeta
        ]);
    }

    public function actionOneOf()
    {
        $controller = \Yii::$app->request->getQueryParam('controller');
        $action = \Yii::$app->request->getQueryParam('action');
        $property = \Yii::$app->request->getQueryParam('property');
        $oneOfId = \Yii::$app->request->getQueryParam('one_of_id');

        $object = \Yii::$app->docParser->load()->getOneOfMethod($controller, $action, $property, $oneOfId);

        if (!$object) {
            throw new NotFoundHttpException();
        }
        return $this->render('@doc/views/objects/view', [
            'object' => $object,
        ]);
    }
}
