<?php

namespace app;


use app\traits\TSingleton;

class Request
{
    use TSingleton;

    /**
     * @var array
     */
    private static $properties = [];

    /**
     * @return bool|void
     */
    private static function init()
    {
        self::init();
        
        if (!isset($_SERVER['REQUEST_METHOD'])) return false;

        self::$properties = [
            'post' => $_POST,
            'get' => $_GET,
            'server' => $_SERVER,
            'files' => $_FILES
        ];
    }

    /**
     * @return array
     */
    public static function post() : array
    {
        self::init();
        return static::$properties['post'];
    }

    /**
     * @return array
     */
    public static function get()  : array
    {
        self::init();
        return static::$properties['get'];
    }

    /**
     * @return array
     */
    public static function server() : array
    {
        self::init();
        return static::$properties['server'];
    }

    /**
     * @return array
     */
    public static function files() : array
    {
        self::init();
        return static::$properties['files'];
    }

    /**
     * @return bool
     */
    public static function isGet()
    {
        return ($_SERVER['REQUEST_METHOD'] === 'GET');
    }

    /**
     * @return bool
     */
    public static function isPost()
    {
        return ($_SERVER['REQUEST_METHOD'] === 'POST');
    }

    /**
     * @param string $key
     * @param string $type
     * @return mixed|null
     */
    function getProperty(string $key, string $type)
    {
        self::init();
        if (isset(static::$properties[$type][$key]))
            return static::$properties[$type][$key];
        return null;
    }

    /**
     * @param string $key
     * @param $val
     * @param string $type
     */
    function setProperty(string $key, $val, string $type)
    {
        self::init();
        static::$properties[$type][$key] = $val;
    }

}