<?php
/**
 * Created by PhpStorm.
 * User: alekseylaptev
 * Date: 18.02.15
 * Time: 20:13
 */

namespace app\components\widgets;

use yii\base\Widget;

class ObjectAttrTableWidget extends Widget
{

    public $object;

    public $showRequired = false;

    public $showNull = true;

    public function run()
    {
        return $this->render('objectAttrTableWidget', [
            'object' => $this->object
        ]);
    }

}