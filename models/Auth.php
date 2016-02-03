<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth".
 *
 * @property string $id
 * @property string $apikey
 * @property string $user_id
 * @property string $created_at
 * @property string $modified_at
 * @property string $expired_at
 * @property string $device_type
 * @property string $device_token
 * @property string $device_id
 * @property integer $api_version
 * @property string $token_type
 *
 * @property User $user
 */
class Auth extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['apikey', 'user_id', 'device_id', 'api_version'], 'required'],
            [['user_id', 'api_version'], 'integer'],
            [['created_at', 'modified_at', 'expired_at'], 'safe'],
            [['device_type', 'token_type'], 'string'],
            [['apikey', 'device_token', 'device_id'], 'string', 'max' => 255],
            [['apikey'], 'unique'],
            [['device_id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'apikey' => 'Apikey',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
            'expired_at' => 'Expired At',
            'device_type' => 'Device Type',
            'device_token' => 'Device Token',
            'device_id' => 'Device ID',
            'api_version' => 'Api Version',
            'token_type' => 'Token Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
