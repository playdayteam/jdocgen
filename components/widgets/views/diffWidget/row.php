<?php

use app\components\DiffRender;

?>


<tr>
    <td>
        <?= DiffRender::renderOp($data['op']) ?>
    </td>
    <td>
        <?= $data['path'] ?>
    </td>
    <td class="danger col-md-3">
        <?= $data['prev_value'] ?>
    </td>
    <td class="success col-md-3">
        <?= is_array($data['new_value']) ? 'array' : $data['new_value'] ?>
    </td>
</tr>
