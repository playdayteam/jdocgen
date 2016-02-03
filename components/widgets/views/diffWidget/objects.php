<?php

use app\components\DiffRender;

?>


<h4>Objects</h4>


<?php

$colspan = 4;

$metaAttrs = ['required'];

?>

<table class="table">
    <?php foreach ($objects as $objectId => $object): ?>
        <tr class="info">
            <th colspan="<?= $colspan ?>">
                <?= DiffRender::renderObjectName($objectId) ?>
            </th>
        </tr>
        <?php if (isset($object['diff'])): ?>
            <tr>
                <td>
                    <?= DiffRender::renderOp($object['diff']['op']) ?>
                </td>
                <td colspan="<?= $colspan - 1 ?>">
                    <p class="bg-success text-center">
                        <?= $object['diff']['comment'] ?>
                    </p>
                </td>
            </tr>
        <?php endif ?>


        <?php if (isset($object['required'])): ?>
            <tr class="warning">
                <th colspan="<?= $colspan ?>">Мета</th>
            </tr>
            <?php foreach ($metaAttrs as $attr): ?>
                <?php if (isset($object[$attr])): ?>
                    <tr>
                        <th class="active" colspan="<?= $colspan ?>">
                            required
                        </th>
                    </tr>
                    <?= $this->render('row', ['data' => $object['required']]) ?>
                <?php endif ?>
            <?php endforeach ?>
        <?php endif ?>

        <?php if (isset($object['properties'])): ?>
            <tr class="warning">
                <th colspan="<?= $colspan ?>">
                    Properties
                </th>
            </tr>
            <?php foreach ($object['properties'] as $propId => $property): ?>
                <tr>
                    <th class="active" colspan="<?= $colspan ?>">
                        <?= $propId ?>
                    </th>
                </tr>
                <?php foreach ($property as $key => $data): ?>
                    <?= $this->render('row', ['data' => $data]) ?>
                <?php endforeach ?>
            <?php endforeach ?>
        <?php endif ?>
    <?php endforeach ?>
</table>