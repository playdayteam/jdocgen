<?php

/**
 * @var $this \yii\web\View
 */

$versions = \app\models\JsonHistory::getLast2Versions();
?>

<div role="tabpanel" style="margin-top: 20px">

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#current" aria-controls="history" role="tab" data-toggle="tab">
                Changes in current version &mdash; <strong><?= Yii::$app->docParser->getVersion() ?></strong>
            </a>
        </li>
        <?php if (count($versions) == 2): ?>
            <li role="presentation">
                <a href="#prev" aria-controls="home" role="tab" data-toggle="tab">
                    The difference between the versions &mdash; <?= $versions[0] ?> and <?= $versions[1] ?>
                </a>
            </li>
        <?php endif ?>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="current">
            <?= $this->render('_currentDiff', ['models' => $models]) ?>
        </div>
        <?php if (count($versions) == 2): ?>
            <div role="tabpanel" class="tab-pane" id="prev">
                <?= $this->render('_crossVersionDiff', ['data' => $crossVersionData]) ?>
            </div>
        <?php endif ?>
    </div>
</div>


