<?php
/**
 * Created by PhpStorm.
 * User: kudinov
 * Date: 10.05.2017
 * Time: 16:43
 */

namespace app\traits;


trait TSingleton
{
    /**
     * @var null|self
     */
    private static $instance = null;

    /**
     * App constructor.
     */
    private function __construct()
    {
    }

    /**
     * @return self
     */
    private static function init()
    {
        if (is_null(static::$instance))
            static::$instance = new static;
        return static::$instance;
    }
}