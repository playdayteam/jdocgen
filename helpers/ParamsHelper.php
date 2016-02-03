<?php


namespace app\helpers;

use Yii;

class ParamsHelper
{
    /**
     * @return int
     */
    public static function getVersion()
    {
//        return (int)Yii::$app->params['version'];
        return 7;
    }

    /**
     * @return int
     */
    public static function newNotificationEnabled()
    {
        return (bool)Yii::$app->params['newNotificationEnabled'];
    }

    /**
     * @return int
     */
    public static function getAndroidVersion()
    {
        return (int)\Yii::$app->params['supportedVersionSinceAndroid'];
    }


    /**
     * @return string
     */
    public static function getIosVersion()
    {
        return \Yii::$app->params['supportedVersionSinceIos'];
    }

    /**
     * @return bool
     */
    public static function isTest()
    {
        return isset($_REQUEST['is_test']) && $_REQUEST['is_test'];
    }

    /**
     * @return bool
     */
    public static function isDebug()
    {
        return isset(Yii::$app->params['isDebug']) && Yii::$app->params['isDebug'];
    }

    /**
     * @return int|null
     */
    public static function getTestToken()
    {
        if (isset(Yii::$app->params['isTest'])) {
            return isset($_REQUEST['test_token']) ? (int)$_REQUEST['test_token'] : (int)getenv('TEST_TOKEN');
        }
        return null;
    }

    /**
     * @return array
     */
    public static function getAllowedVersions()
    {
        return explode(',', Yii::$app->params['allowedVersions']);
    }

    /**
     * @return int
     */
    public static function getAllowedVersionMax()
    {
        return max(self::getAllowedVersions());
    }


    /**
     * @return boolean
     */
    public static function isGenerateNotifications()
    {
        return Yii::$app->params['generateNotifications'];
    }

    /**
     * @return array
     */
    public static function getModeratorProfileIds()
    {
        return explode(',', Yii::$app->params['moderator_profile_ids']);
    }

    /**
     * @return bool
     */
    public static function isCli()
    {
        return php_sapi_name() == 'cli';
    }

    public static function isGroupPush()
    {
        return Yii::$app->params['groupPush'];
    }

    /**
     * @return string
     */
    public static function getFastcgiFinishRequest()
    {
        return Yii::$app->params['fastcgiFinishRequest'];
    }
}