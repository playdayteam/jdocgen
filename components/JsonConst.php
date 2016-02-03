<?php
namespace app\components;

abstract class JsonConst
{
    const DESCRIPTION = 'description';
    const TYPE = 'type';
    const ENUM = 'enum';
    const ITEM = 'item';

    /**
     * разрешено значение null в поле
     */
    const NULL = 'null';

    /**
     * поле не верзвращать в случае значения null
     */
    const SKIP_NULL = 'skip_null';

    /**
     * название поля в модели, по которому получать значение
     */
    const FIELD = 'field';
    /**
     * callback функция получения значения
     */
    const VALUE = 'value';
    const META = 'meta';
    /**
     * значение в случае null
     */
    const DEFAULT_VALUE = 'default_value';

    const TYPE_INTEGER = 'integer';
    const TYPE_FLOAT = 'float';
    const TYPE_STRING = 'string';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_OBJECT = 'object';
    const TYPE_ARRAY = 'array';
    const TYPE_PARAMS = 'params';


}