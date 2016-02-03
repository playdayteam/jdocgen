<?php


namespace app\helpers;


use app\helpers\PinbaHelper;
use Yii;

class JsonHelper
{
    /**
     * @param mixed $data
     * @return string
     */
    public static function encode($data)
    {
        $res = @json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        // encode не прошел. Если ошибка говорит что неподдерживаемый тип, то фильтруем данные и снова encode
        if (!$res && json_last_error() == JSON_ERROR_UNSUPPORTED_TYPE) {
            JsonHelper::filterData($data);
            $res = @json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
        return $res;
    }

    /**
     * @param string $str
     * @return mixed
     */
    public static function decode($str)
    {
        $result = json_decode($str, true);
        return $result;
    }

    /**
     * фильтр данных для Json_encode - фильтруем ресурсы
     * @param array $data
     */
    public static function filterData(&$data)
    {
        foreach ($data as $key => &$val) {
            if (is_array($val)) {
                self::filterData($val);
            } elseif (!is_null(@get_resource_type($val))) {
                $val = 'Resource ' . get_resource_type($val);
            }
        }
    }
}