<?php

namespace app\exceptions;


use Noodlehaus\Exception;

class FileException extends Exception
{
    public static function maxFileSizeExceeded(string $name) : self
    {
        return new self('Превышен максимальный размер файла ' . $name);
    }

    public static function deleteFileFailure(string $name) : self
    {
        return new self('Не удалось удалить файл ' . $name);
    }

    public static function renameFileFailure(string $name) : self
    {
        return new self('Не удалось переименовать файл ' . $name);
    }

    public static function uploadFileFailure(string $name) : self
    {
        return new self('Не удалось загрузить файл ' . $name);
    }

    public static function invalidExtension(string $name) : self
    {
        return new self('Неверное расширение файла ' . $name);
    }

    public static function fileNameLengthExceeded(int $length) : self
    {
        return new self('Имя файла длиннее чем ' . $length . ' символов');
    }
}