<?php

namespace app\controllers;

use app\models\SourceForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        } else {
            Yii::$app->docParser->load();
            return $this->render('index');

        }
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
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
