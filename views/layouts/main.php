<?php
use yii\helpers\Html;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */
AppAsset::register($this);


$this->title = Yii::$app->params['name'];
$this->registerMetaTag([
    'name' => 'description',
    'content' => Yii::$app->params['description']
])

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
<?= \app\components\widgets\NavBarWidget::widget() ?>

<div class="row-offcanvas row-offcanvas-left">
    <div id="sidebar" class="sidebar-offcanvas">
        <div class="col-md-12">
            <?php if (!Yii::$app->user->isGuest): ?>
                <div>
                    <a class="btn btn-danger" href="/build">Build doc</a>
                    <hr>
                    <a href="/">Main page</a></div>
                <?= \app\components\widgets\MethodsMenuWidget::widget() ?>
                <?= \app\components\widgets\ObjectsMenuWidget::widget() ?>
            <?php endif ?>
        </div>
    </div>
    <div id="main">
        <div class="col-md-12">
            <?php
            $message = Yii::$app->session->getFlash('message');
            ?>
            <?php if ($message[0]): ?>
                <div class="alert alert-success alert-dismissible">
                    <?= $message[0] ?>
                </div>
            <?php endif ?>
            <?= $content ?>
        </div>
    </div>
</div><!--/row-offcanvas -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
