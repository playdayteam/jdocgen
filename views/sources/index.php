<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SourcesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sources';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sources-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="alert alert-warning">
        Каталог с документацией берется в соответствии с доменом где открыт <?= Yii::$app->params['name'] ?>.
        <br/>
        Если совпадение не найдено, берется
        <strong>
            <?= \app\models\Sources::getCurrentSource() ?>
        </strong>
        <br/>
        Пример домена: <strong>doc.example.com</strong>
    </div>

    <p>
        <?= Html::a('Create Sources', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'domain',
            'dir:ntext',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
