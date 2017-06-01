<?php

namespace app\classes;


use app\App;
use app\helpers\Filter;
use app\Request;
use app\View;

class FileManager extends AFileComposite
{
    /**
     * @var string путь по рабочей директории
     */
    private $basePath = '';

    /**
     * @var string
     */
    private $path = '';

    /**
     * @var string
     */
    private $backLink = '';

    public function __construct()
    {
        $this->initBasePath();
        $this->initCurrentPath();
        $this->initBackLink();
        parent::__construct($this->path);
        $this->setFiles();
    }

    private function setFiles()
    {
        if ($this->isDir()) {
            /** @var AFileComposite $file */
            $this->initFiles();
        } else {
            /** @var AFileLeaf $file */
            //$this->download();
        }
    }

    private function initCurrentPath()
    {
        $path = Request::get('path');
        if ($path) {
            $path = Filter::handlePath($path);
            if (mb_strpos($path, $this->basePath) === false)
                return;
            $this->path = $path;
        } else {
            $this->path = $this->basePath;
        }
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

    private function initBackLink()
    {
        if ($this->path !== $this->basePath) {
            $backLink = mb_substr($this->path, 0, mb_strrpos($this->path, DIRECTORY_SEPARATOR));
            $this->backLink = $backLink;
        }
    }

    /**
     * @return string
     */
    public function getBackLink()
    {
        return $this->backLink;
    }

}