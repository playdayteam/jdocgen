<?php

$colspan = 4;

/**
 * @var \yii\web\View $this
 */

?>

<h4>Methods</h4>

<table class="table">
    <?php foreach ($methods as $controller => $methodData): ?>
        <?php foreach ($methodData['actions'] as $action => $actionData): ?>
            <tr>
                <th class="info" colspan="<?= $colspan ?>">
                    <?= $controller . '/' . $action ?>
                </th>
            </tr>
            <?php foreach ($actionData['properties'] as $propertyId => $data): ?>
                <tr>
                    <th class="active" colspan="<?= $colspan ?>">
                        <?= $propertyId ?>
                    </th>
                </tr>
                <?= $this->render('row', ['data' => $data]) ?>
            <?php endforeach ?>
        <?php endforeach ?>
    <?php endforeach ?>
</table>