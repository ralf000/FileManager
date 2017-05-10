<?php

namespace app;


use app\traits\TSingleton;

class App
{
    use TSingleton;

    /**
     * @var array Глобальные объекты приложения
     */
    private $objects = [];

    /**
     * @param string $objectName
     * @return object|null
     */
    public static function get(string $objectName)
    {
        return self::init()->getObject($objectName);
    }

    /**
     * @param string $name
     * @param $object
     * @throws \TypeError
     */
    public static function set(string $name, $object)
    {
        self::init()->setObject($name, $object);
    }

    /**
     * @param string $objectName
     * @return object|null
     */
    private function getObject(string $objectName)
    {
        if (!array_key_exists($objectName, $this->objects))
            return null;
        return $this->objects[$objectName];
    }

    /**
     * @param string $objectName
     * @param $object
     * @throws \TypeError
     */
    private function setObject(string $objectName, $object)
    {
        if (!is_object($object))
            throw new \TypeError();
        if (!in_array($objectName, $this->objects))
            $this->objects[$objectName] = $object;
    }
}