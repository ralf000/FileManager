<?php
/**
 * Created by PhpStorm.
 * User: kudinov
 * Date: 12.05.2017
 * Time: 11:46
 */

namespace app\exceptions;


class APIException extends \Exception
{
    public static function methodNotAllowed(string $class, string $method) : self
    {
        return new self('Использование метода ' . $method . ' класса ' . $class . ' запрещено');
    }
}