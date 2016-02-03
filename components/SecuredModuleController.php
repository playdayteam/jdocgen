<?php

namespace app\components;

use app\models\LoginForm;
use Yii;

class SecuredModuleController extends \yii\web\Controller
{
    protected function actionLogin()
    {
        $moduleName = $this->getModuleName();
        if (
            !Yii::$app->user->isGuest &&
            Yii::$app->user->getIdentity() && Yii::$app->user->getIdentity()->isAllowForModule($moduleName)
        ) {
            return $this->redirect(["/{$moduleName}"]);
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(["/{$moduleName}/login"]);
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    protected function actionLogout()
    {
        $moduleName = $this->getModuleName();
        Yii::$app->user->logout();
        return $this->redirect(["/{$moduleName}/login"]);
    }

    protected function getModuleName()
    {
        return Yii::$app->controller->module->id;
    }
}