<?php

namespace app\controllers;

use app\components\SecuredModuleController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class ObjectsController extends SecuredModuleController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionView($id)
    {
        $object = \Yii::$app->docParser->load()->getObject($id);
        if (!$object) {
            throw new NotFoundHttpException('Object not found');
        }
        return $this->render('view', ['object' => $object]);
    }

    public function actionOneOf()
    {
        $object = \Yii::$app->request->getQueryParam('object');
        $property = \Yii::$app->request->getQueryParam('property');
        $oneOfId = \Yii::$app->request->getQueryParam('one_of_id');
        $object = \Yii::$app->docParser->load()->getOneOfObject($object, $property, $oneOfId);
        if (!$object) {
            throw new NotFoundHttpException();
        }
        return $this->render('view', ['object' => $object]);
    }

}
