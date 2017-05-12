<?php

return ['main' =>
    [
        'rootPath' => $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . DIRECTORY_SEPARATOR . 'upload',
        'basePath' => 'upload',
        'maxFileSize' => 1024 * 1024 * 3,
        'maxFileName' => 20,
        'allowExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt'],
        'imgExtensions' => ['jpg', 'jpeg', 'gif', 'png'],
        'fileExtensions' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt'],
    ]
];