<?php

namespace app\classes;


use app\App;
use app\View;

class FileManager extends AFileComposite
{
    /**
     * @var string путь по рабочей директории
     */
    private $basePath = '';

    public function __construct($path = '')
    {
        $this->initBasePath();
        if (empty($path)){
            $path = $this->basePath;
        }
        parent::__construct($path);
    }

    /**
     * Получает путь до рабочей директории приложения
     * Создает рабочую директорию если та не существует
     */
    private function initBasePath()
    {
        $this->basePath = App::get('config')->get('main.basePath');
        if (!file_exists($this->basePath)) {
            mkdir($this->basePath, 0777, true);
        }
    }

}