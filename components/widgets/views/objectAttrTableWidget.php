<?php
/**
 * @var $this \yii\base\View
 */

use app\components\ObjectRender;

$showRequired = $this->context->showRequired;
$showNull = $this->context->showNull;

$colspan = 3 + $showRequired + $showNull;

?>

<table class="table">
    <tr>
        <th class="col-md-3">
            Param
        </th>
        <th class="col-md-1">
            Type
        </th>
        <?php if ($showRequired): ?>
            <th class="col-md-1">
                Required
            </th>
        <?php endif ?>
        <?php if ($showNull): ?>
            <th class="col-md-1">
               Null
            </th>
        <?php endif ?>
        <th>
            Description
        </th>
    </tr>

    <?php if (isset($object['properties']) && count($object['properties'])): ?>
        <?php foreach ($object['properties'] as $id => $data): ?>
            <tr>
                <td>
                    <?= $id ?>
                </td>
                <td>
                    <?= ObjectRender::renderType($data) ?>
                    <?php if (!empty($data['is_relation'])): ?>
                        <span class="label label-success">relation</span>
                    <?php endif; ?>
                </td>
                <?php if ($showRequired): ?>
                    <td>
                        <?php if (isset($object['required']) && is_array($object['required']) && in_array($id, $object['required'])): ?>
                            <span class="label label-success">Yes</span>
                        <?php else: ?>
                            <span class="label label-danger">No</span>
                        <?php endif ?>
                    </td>
                <?php endif ?>
                <?php if ($showNull): ?>
                    <td>
                        <?= ObjectRender::renderNull($data) ?>
                    </td>
                <?php endif ?>
                <td>
                    <?= ObjectRender::renderDescription($data, $id) ?>
                </td>
            </tr>
        <?php endforeach ?>
    <?php else: ?>
        <tr>
            <td colspan="<?= $colspan ?>">
                No data
            </td>
        </tr>
    <?php endif ?>
</table>