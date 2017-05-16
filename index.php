<?php
//init strict mode
declare(strict_types = 1);

require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/bootstrap/app.php';

try {
    $fileManager = new \app\classes\FileManager();
} catch (Exception $e) {
    dd($e);
}