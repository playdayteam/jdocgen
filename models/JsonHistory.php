<?php

namespace app\models;

use app\components\JsonSchemaDiff;
use Yii;

/**
 * This is the model class for table "json_history".
 *
 * @property integer $id
 * @property string $hash
 * @property integer $version
 * @property string $content
 * @property integer $size
 * @property integer $created_at
 */
class JsonHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'json_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hash', 'version', 'content', 'size', 'created_at'], 'required'],
            [['version', 'size', 'created_at'], 'integer'],
            [['content'], 'string'],
            [['hash'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hash' => 'Hash',
            'version' => 'Version',
            'content' => 'Content',
            'size' => 'Size',
            'created_at' => 'Created At',
        ];
    }

    public function getDateTitle()
    {
        if ($this->created_at >= strtotime("today")) {
            $result = "Today, " . date('H:i:s', $this->created_at);
        } else {
            if ($this->created_at >= strtotime("yesterday")) {
                $result = "Yesterday, " . date('H:i:s', $this->created_at);
            } else {
                $result = date('Y-m-d H:i:s');
            }
        }
        return $result;
    }

    public function getDiffData()
    {
        $modelPrev = self::find()->where('version = :version AND id < :id', [':version' => $this->version, ':id' => $this->id])->orderBy('id DESC')->one();

        $result = [];

        if ($modelPrev) {
            $result = JsonSchemaDiff::diff(json_decode($modelPrev->content, 1), json_decode($this->content, 1));
        }

        return $result;
    }

    public static function getCrossVersionDiffData()
    {
        $result = [];

        //берем две последние версии
        $versions = self::getLast2Versions();

        if (count($versions) == 2) {
            $modelPrev = JsonHistory::find()->where(['version' => $versions[0]])->orderBy('id DESC')->one();
            $modelCurrent = JsonHistory::find()->where(['version' => $versions[1]])->orderBy('id DESC')->one();
            if ($modelPrev && $modelCurrent) {
                $result = JsonSchemaDiff::diff(json_decode($modelPrev->content, 1), json_decode($modelCurrent->content, 1));
            }
        }

        return $result;
    }

    public static function getLast2Versions()
    {
        $sql = 'SELECT DISTINCT version FROM json_history ORDER BY version DESC LIMIT 2';
        $versions = Yii::$app->db->createCommand($sql)->queryColumn();
        return array_reverse($versions);
    }
}
