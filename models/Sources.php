<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sources".
 *
 * @property integer $id
 * @property string $domain
 * @property string $dir
 */
class Sources extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sources';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['domain', 'dir'], 'required'],
            [['dir'], 'validatekDir'],
            [['dir'], 'string'],
            [['domain'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'domain' => 'Domain',
            'dir' => 'Dir',
        ];
    }

    public function validatekDir($attr, $params)
    {
        if (!file_exists($this->$attr)) {
            $this->addError($attr, 'Каталог не существует');
        }
    }

    public static function getCurrentDomain()
    {
        return Yii::$app->request->serverName;
    }

    public static function getCurrentSource()
    {
        $model = self::findOne(['domain' => self::getCurrentDomain()]);
        if ($model) {
            $dir = $model->dir;
            if (file_exists($dir)) {
                return $dir;
            } else {
                return self::getDefaultSource();
            }
        } else {
            return self::getDefaultSource();
        }
    }

    public static function getDefaultSource()
    {
        return Yii::getAlias('@app') . '/json-schema';
    }
}
