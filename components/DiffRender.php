<?php
/**
 * Created by PhpStorm.
 * User: alekseylaptev
 * Date: 20.03.15
 * Time: 16:13
 */

namespace app\components;


use yii\helpers\Html;
use yii\helpers\Url;

class DiffRender
{

    public static function renderObjectName($objectId)
    {
        $result = Html::a($objectId, Url::to(['objects/' . $objectId]));
        return $result;
    }

    public static function renderOp($op)
    {
        $map = [
            'add' => 'success',
            'replace' => 'warning',
            'remove' => 'danger'
        ];

        $class = isset($map[$op]) ? $map[$op] : 'default';

        $content = $op;

        return Html::tag('span', $content, ['class' => 'label label-' . $class]);
    }

}