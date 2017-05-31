<?php

namespace app\helpers;


class Filter
{

    public static function handlePath(string $path) : string
    {
        $path = filter_var($path, FILTER_SANITIZE_STRING);
        $path = trim($path, '/\\');
        $path = str_replace('/', DIRECTORY_SEPARATOR, $path);
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);

        return $path;
    }

}