<h1><?= ucfirst($object['id']) ?></h1>

<?php if (isset($object['title'])): ?>
    <h4><?= $object['title'] ?></h4>
<?php endif ?>

<?php if (isset($object['description'])): ?>
    <p>
        <?= $object['description'] ?>
    </p>
<?php endif ?>

<h3>Fields</h3>
<?= \app\components\widgets\ObjectAttrTableWidget::widget(['object' => $object, 'showRequired' => true]) ?>