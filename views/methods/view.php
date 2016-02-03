<?php

use app\components\widgets\ObjectAttrTableWidget;

?>
    <h2><?= $controllerMeta['title'] ?> <small>/ <?= $method['title'] ?></small></h2>

    <h3>Method description</h3>

<?= isset($method['description']) ? $method['description'] : 'empty' ?>

    <h3>Signature</h3>

    <table class="table">
        <tr>
            <th class="col-md-4">
                URI
            </th>
            <th>
                Метод
            </th>
        </tr>
        <tr>
            <td>
                <?= $method['request']['links'][0]['href'] ?>
            </td>
            <td>
                <?= strtoupper($method['request']['method']) ?>
            </td>
        </tr>
    </table>

    <h3>Request params</h3>
<?php if (isset($method['request']['properties'])): ?>
    <?= ObjectAttrTableWidget::widget([
        'object' => $method['request'],
        'showNull' => false,
        'showRequired' => true
    ]) ?>
<?php else: ?>
    empty
<?php endif ?>

    <h3>Ответ</h3>

    <h4>result.general</h4>

<?php

$general = isset($method['response']['properties']['result']['properties']['general']) ? $method['response']['properties']['result']['properties']['general'] : null;

?>

<?php if ($general): ?>
    <?php
    $object = [
        'properties' => $general['properties'],
        'required' => isset($general['required']) ? $general['required'] : null
    ];
    ?>
    <?= ObjectAttrTableWidget::widget(['object' => $object, 'showRequired' => true]) ?>
<?php else: ?>
    No data
<?php endif ?>

<?php

$data = isset($method['response']['properties']['result']['properties']['data']) ? $method['response']['properties']['result']['properties']['data'] : null;

?>



    <h4>result.data</h4>

<?php if ($data): ?>

    <?php

    $object = [
        'properties' => [
            'data' => $data
        ]
    ]

    ?>

    <?= ObjectAttrTableWidget::widget(['object' => $object, 'showRequired' => true]) ?>

<?php else: ?>

    No data

<?php endif ?>


<?php if (isset($method['meta']['md'])): ?>

    <hr>

    <?= $method['meta']['md'] ?>

<?php endif ?>