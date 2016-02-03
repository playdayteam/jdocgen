<?php

namespace app\components\widgets;

use app\components\exceptions\AppHttpException;
use app\helpers\PHPDocHelper;
use Yii;
use yii\base\Widget;

class ReviewWidget extends Widget
{

    public function run()
    {
        $data = \Yii::$app->docParser->getContent();
        $stat = $data['stat'];
        return $this->render('reviewWidget', [
                'stat' => $stat,
            ]
        );
    }

}