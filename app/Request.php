<?php

namespace app;


use app\classes\FileManager;
use app\helpers\Text;
use app\traits\TSingleton;

class Request
{
    use TSingleton;

    /**
     * @var array
     */
    private static $properties = [];

    public static function initRequest()
    {
        self::init();
        self::handlePostRequest();
    }

    private static function handlePostRequest()
    {
        if (!self::isPost()) return;

        /** @var FileManager $fileManager */
        $fileManager = App::get('fileManager');
        $command = self::post('command') ?? '';
        switch ($command) {
            case 'file-rename':
                $id = filter_var(self::post('id'), FILTER_SANITIZE_NUMBER_INT);
                $newName = filter_var(self::post('newName'), FILTER_SANITIZE_STRING);
                if (preg_match('/[а-яА-ЯёЁ]/u', $newName)){
                    $newName = Text::translit($newName);
                }
                if (!is_numeric($id) || !$newName) return;
                $file = $fileManager->getFile($id);
                $file->rename($newName);
                break;
            case 'file-delete':
                $id = filter_var(self::post('id'), FILTER_SANITIZE_NUMBER_INT);
                if (!is_numeric($id)) return;
                $file = $fileManager->getFile($id);
                $file->remove();
                break;
            case 'file-upload':
                $fileManager->upload();
                break;
        }
    }

    /**
     * @return bool|void
     */
    private static function initRequestVars()
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
     * @param string $name
     * @return array
     */
    public static function post(string $name = '')
    {
        self::initRequestVars();
        if (!empty($name)) {
            return static::$properties['post'][$name] ?? null;
        }
        return static::$properties['post'];
    }

    /**
     * @param string $name
     * @return mixed
     */
    public static function get(string $name = '')
    {
        self::initRequestVars();
        if (!empty($name)) {
            return static::$properties['get'][$name] ?? null;
        }
        return static::$properties['get'];
    }

    /**
     * @return array
     */
    public static function server() : array
    {
        self::initRequestVars();
        return static::$properties['server'];
    }

    /**
     * Для нескольких файлов
     *
     * @return array
     */
    public static function files() : array
    {
        self::initRequestVars();
        return current(static::$properties['files']);
    }

    /**
     * Для одиночного файла
     *
     * @return array
     */
/*    public static function file() : array
    {
        self::initRequestVars();
        return current(static::$properties['files']);
    }*/

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
        self::initRequestVars();
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
        self::initRequestVars();
        static::$properties[$type][$key] = $val;
    }

}