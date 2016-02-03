<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "profile".
 *
 * @property string $id
 * @property string $created_at
 * @property string $modified_at
 * @property string $user_id
 * @property string $circle_id
 * @property string $avatar
 * @property integer $rank
 * @property string $name
 * @property string $background
 * @property string $visibility
 * @property string $chat_id
 *
 * @property Badge[] $badges
 * @property Chat[] $chats
 * @property ChatMember[] $chatMembers
 * @property ChatMessage[] $chatMessages
 * @property CircleInvite[] $circleInvites
 * @property EventMember[] $eventMembers
 * @property Friendship[] $friendships
 * @property Post[] $posts
 * @property Chat $chat
 * @property Circle $circle
 * @property User $user
 * @property ProfilePlace[] $profilePlaces
 * @property SystemInvite[] $systemInvites
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'modified_at'], 'safe'],
            [['user_id', 'name', 'visibility', 'circle_logo'], 'required'],
            [['user_id', 'circle_id', 'chat_id'], 'integer'],
            [['visibility', 'privacy'], 'string'],
            [['avatar', 'name', 'background', 'circle_logo'], 'string', 'max' => 255],
            [['user_id', 'circle_id'], 'unique', 'targetAttribute' => ['user_id', 'circle_id'], 'message' => 'The combination of User ID and Circle ID has already been taken.'],
            [['chat_id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
            'user_id' => 'User ID',
            'circle_id' => 'Circle ID',
            'avatar' => 'Avatar',
            'name' => 'Name',
            'background' => 'Background',
            'visibility' => 'Visibility',
            'circle_logo' => 'Circle Logo',
            'chat_id' => 'Chat ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBadges()
    {
        return $this->hasMany(Badge::className(), ['agg_profile_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChatMembers()
    {
        return $this->hasMany(ChatMember::className(), ['profile_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChatMessages()
    {
        return $this->hasMany(ChatMessage::className(), ['profile_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCircleInvites()
    {
        return $this->hasMany(CircleInvite::className(), ['inviter_profile_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventMembers()
    {
        return $this->hasMany(EventMember::className(), ['profile_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFriendships()
    {
        return $this->hasMany(Friendship::className(), ['whom_profile_id' => 'id', 'agg_whom_user_id' => 'user_id', 'agg_circle_id' => 'circle_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['agg_author_profile_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChat()
    {
        return $this->hasOne(Chat::className(), ['id' => 'chat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCircle()
    {
        return $this->hasOne(Circle::className(), ['id' => 'circle_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfilePlaces()
    {
        return $this->hasMany(ProfilePlace::className(), ['profile_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSystemInvites()
    {
        return $this->hasMany(SystemInvite::className(), ['inviter_profile_id' => 'id']);
    }
}
