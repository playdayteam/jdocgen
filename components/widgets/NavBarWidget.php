<?php

namespace app\components\widgets;

use app\helpers\ParamsHelper;
use app\models\SourceForm;
use yii\base\Widget;

class NavBarWidget extends Widget
{

    public function run()
    {

        $model = new SourceForm();
        $model->version = \Yii::$app->docParser->load()->getVersion();

        $versions = \Yii::$app->docParser->load()->getVersions();

        $versions = array_flip($versions);
        foreach ($versions as $key => $value) {
            $versions[$key] = 'Version ' . $key;

            if ($key == ParamsHelper::getVersion()) {
                $versions[$key] .= ' (on server)';
            }
        }


        return $this->render('navBarWidget', [
            'model' => $model,
            'versions' => $versions
        ]);
    }

}