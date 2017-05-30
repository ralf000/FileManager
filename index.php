<?php
//init strict mode
declare(strict_types = 1);

require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/bootstrap/app.php';

try {
    $fileManagerController = new \app\classes\FileManagerController();
    $fileManagerController->run();
} catch (Exception $e) {
    echo $e;
}