<h3>
    Типы ошибок (type)
</h3>
<table class="table">
    <tr>
        <th>
            type
        </th>
        <th>
            Http-code
        </th>
        <th>
            Description
        </th>
        <th>
            Возможные значения message
        </th>
    </tr>
    <?php foreach ($typeConstantsMap as $const => $value): ?>
        <tr>
            <td>
                <code><?= $value ?></code>(<?= $const ?>)
            </td>
            <td>
                <?= implode(', ', \app\components\exceptions\AppHttpException::getHttpCodesByType($value) ?: []) ?>
            </td>

            <td>
                <?= isset($constantDescriptions[$const]) ? $constantDescriptions[$const] : ''; ?>
            </td>
            <td>
                <ul>
                    <?php foreach ($idConstantsByTypeConstantValuesMap[$value] as $idConstant): ?>
                        <li>
                            <code><?= trim(\app\components\exceptions\AppHttpException::getMessageByCode($idConstantsMap[$idConstant])) ?></code>(<?= $idConstant ?>
                            )
                        </li>
                    <?php endforeach ?>
                </ul>
            </td>
        </tr>
    <?php endforeach ?>
</table>