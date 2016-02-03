<?php
/**
 * Created by PhpStorm.
 * User: alekseylaptev
 * Date: 20.03.15
 * Time: 15:20
 */

namespace app\components\widgets;


use app\models\JsonHistory;
use yii\bootstrap\Widget;

class DiffWidget extends Widget
{

    public function run()
    {
        $models = JsonHistory::find()->where(['version' => \Yii::$app->docParser->getVersion()])->orderBy('created_at DESC')->all();

        $crossVersionData = JsonHistory::getCrossVersionDiffData();
        return $this->render('diffWidget/main', [
            'crossVersionData' => $crossVersionData,
            'models' => $models
        ]);
    }

}