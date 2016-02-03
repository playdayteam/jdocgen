<?php
/**
 * Created by PhpStorm.
 * User: alekseylaptev
 * Date: 01.02.16
 * Time: 19:54
 */

namespace app\controllers;


use app\components\JsonSchemaValidator;
use yii\web\Controller;

class BuildController extends Controller
{

    public function actionIndex()
    {
        $validator = new JsonSchemaValidator();
        $versions = $validator->getAvailableVersions();
        foreach ($versions as $version) {
            $v = new JsonSchemaValidator($version);
            $v->buildDefinitions();
            $v->saveToDb();
        }

        \Yii::$app->session->addFlash('message', 'Документация обновлена');

        return $this->redirect(\Yii::$app->request->referrer);
    }

}