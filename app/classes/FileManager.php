<?php

namespace app\classes;


use app\App;
use app\exceptions\FileException;
use app\helpers\Filter;
use app\helpers\Text;
use app\Request;

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

    public function createFolder(string $name) : bool
    {
        if (!$this->isDir() || !$this->includedBasePath())
            return false;
        $path = $this->getPathname() . DIRECTORY_SEPARATOR . $name;
        if (!mkdir($path)) {
            FileException::createDirFailure($this->getPathname());
        }
        return true;
    }
    
    public function handleFileName(string $name, string $extension = '')
    {
        if (preg_match('/[а-яА-ЯёЁ]/u', $name)){
            $name = Text::translit($name);
        }
        $name = $this->handleDoubleFileName($name, $extension);
        return $name;
    }
    

    /**
     * Обрабатывает имена файлов, который дублируют уже имеющиеся в директории
     * 
     * @param string $name
     * @param string $extension
     * @return string
     */
    private function handleDoubleFileName(string $name, string $extension = '') : string
    {
        $num = 1;
        $files = $this->getFiles();
        foreach ($files as $file) {
            /** @var \SplFileInfo $file */
            if ($file->getFilename() === ($name . $extension)) {
                if (preg_match('~\((\d+\))$~', $name, $match)) {
                    $num = $match[1] + 1;
                }
                return $this->handleDoubleFileName($name . "($num)", $extension);
            }
        }
        return $name;
    }

    private function setFiles()
    {
        if ($this->isDir()) {
            /** @var AFileComposite $file */
            $this->initFiles();
        } else {
            /** @var AFileLeaf $file */
            $this->download();
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
     * Проверяет, включает ли путь файл базовый путь
     *
     * @return bool
     */
    private function includedBasePath()
    {
        $path = ltrim($this->getPathname(), DIRECTORY_SEPARATOR);
        return mb_strpos($path, $this->basePath) === 0;
    }

    /**
     * @return string
     */
    public function getBackLink()
    {
        return $this->backLink;
    }

}