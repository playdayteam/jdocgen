<?php

namespace app\models;

use app\components\DocParser;
use yii\base\Model;

class SourceForm extends Model
{

    public $version;

    public function rules()
    {
        return [
            [['version'], 'required'],
        ];
    }
}