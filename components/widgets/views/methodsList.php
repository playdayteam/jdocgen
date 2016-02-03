<?php

use app\components\ObjectRender;


?>

<table class="table">

    <?php foreach ($methods as $data): ?>
        <tr>
            <th colspan="6" class="info">
                <?= $data['meta']['title'] ?>
            </th>
        </tr>
        <tr class="warning">
            <th>
                id
            </th>
            <th>
                method
            </th>
            <th>
                title
            </th>
            <th>
                meta
            </th>
            <th>
                general
            </th>
            <th>
                data
            </th>
        </tr>
        <?php foreach ($data['actions'] as $actionId => $action): ?>
            <tr>
                <td>
                    <?php

                    $url = \yii\helpers\Url::toRoute([
                        'methods/view',
                        'controller' => $data['meta']['id'],
                        'action' => $actionId
                    ]);

                    $href = isset($action['request']['links'][0]['href']) ? $action['request']['links'][0]['href'] : null;

                    ?>
                    <?= \yii\helpers\Html::a($href, $url) ?>
                </td>
                <td>
                    <?= ObjectRender::renderMethod(isset($action['request']['method']) ? $action['request']['method'] : null) ?>
                </td>
                <td>
                    <?= $action['title'] ?>
                </td>
                <td>
                    <?= ObjectRender::renderBool(isset($action['response']['properties']['meta'])) ?>
                </td>
                <td>
                    <?= ObjectRender::renderBool(isset($action['response']['properties']['result']['properties']['general'])) ?>
                </td>
                <td>
                    <?= ObjectRender::renderBool(isset($action['response']['properties']['result']['properties']['data'])) ?>
                </td>
            </tr>
        <?php endforeach ?>
    <?php endforeach ?>

</table>