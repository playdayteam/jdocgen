<div class="alert alert-info">
    <strong>Domain:</strong> <?= \app\models\Sources::getCurrentDomain() ?>
    |
    <strong>Current source:</strong> <?= \app\models\Sources::getCurrentSource() ?>
    |
    <a class="btn btn-warning btn-xs" href="/sources">Settings</a>

</div>

<?php if (!Yii::$app->user->isGuest): ?>
    <?= \app\components\widgets\ReviewWidget::widget() ?>
<?php endif ?>
