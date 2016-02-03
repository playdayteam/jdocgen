<table class="table">
    <tr class="warning">
        <th>
            Id
        </th>
        <th>
            Title
        </th>
        <th>
            Properties count
        </th>
        <th>
            AdditionalProperties
        </th>
    </tr>
    <?php foreach ($objects as $object): ?>
        <?php if (!in_array($object['id'], ['meta'])): ?>
            <tr>
                <td>
                    <?php

                    $url = \yii\helpers\Url::toRoute(['objects/' . $object['id']]);

                    ?>
                    <?= \yii\helpers\Html::a($object['id'], $url) ?>
                </td>
                <td>
                    <?= isset($object['title']) ? $object['title'] : null ?>
                </td>
                <td>
                    <?= count($object['properties']) ?>
                </td>
                <td>
                    <?php
                    if (isset($object['additionalProperties'])) {
                        if ($object['additionalProperties']) {
                            $class = 'success';
                            $label = 'есть';

                        } else {
                            $class = 'danger';
                            $label = 'нет';
                        }
                    } else {
                        $class = 'success';
                        $label = 'есть';
                    }
                    ?>
                    <span class="label label-<?= $class ?>"><?= $label ?></span>
                </td>
            </tr>
        <?php endif ?>
    <?php endforeach ?>
</table>