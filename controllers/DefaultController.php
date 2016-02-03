<?php

namespace app\controllers;

use app\components\SecuredModuleController;
use app\models\SourceForm;
use Yii;

class DefaultController extends SecuredModuleController
{
    public function actionIndex()
    {
        Yii::$app->docParser->load();
        return $this->render('index');
    }

    public function actionLogin()
    {
        return parent::actionLogin();
    }

    public function actionLogout()
    {
        return parent::actionLogout();
    }

    public function actionChangeSource()
    {
        $form = new SourceForm();
        if (Yii::$app->request->isPost) {
            $form->load(Yii::$app->request->post());
        } else {
            $form->load(Yii::$app->request->getQueryParams(), '');
        }

        if ($form->validate()) {
            Yii::$app->docParser->setVersion($form->version);
        } else {
            var_dump($form->errors);
            die;
        }

        if (Yii::$app->request->isPost) {
            $this->redirect(Yii::$app->request->referrer);
        } else {
            $this->redirect('/');
        }

        return true;
    }

    public function actionRefresh()
    {
        Yii::$app->docParser->refresh();
        $this->redirect(Yii::$app->request->referrer);
        return true;
    }
}
