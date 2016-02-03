<?php

use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\bootstrap\ActiveForm;

NavBar::begin([
    'brandLabel' => Yii::$app->params['name'],
    'brandUrl' => '/',
    'options' => [
        'class' => 'navbar-inverse',
    ],
]);

if (Yii::$app->user->isGuest) {
    $items = [
        [
            'label' => 'Login',
            'url' => ['/site/login']
        ]
    ];
} else {
    $items = [
        [
            'label' => 'Main',
            'url' => ['/']
        ],
        [
            'label' => 'Sources',
            'url' => ['/sources']
        ],
        [
            'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
            'url' => ['/logout'],
            'linkOptions' => ['data-method' => 'post']
        ]
    ];

}

echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $items,
]);


$disabled = Yii::$app->user->isGuest;

$form = ActiveForm::begin([
    'id' => 'list-form',
    'action' => \yii\helpers\Url::to(['default/change-source']),
    'layout' => 'inline',
    'options' => [
        'class' => 'navbar-form navbar-left'
    ]
]);

if (!Yii::$app->user->isGuest) {
    echo '<a href="#" class="navbar-form navbar-left" id="toggle_menu_control" style="font-size: 16px;">[ Hide / Show menu ]</a>';
    echo $form->field($model, 'version')->dropDownList($versions, [
        'id' => 'list-version',
        'style' => 'margin-right: 3px',
        'disabled' => $disabled
    ]);
}

ActiveForm::end();

NavBar::end();