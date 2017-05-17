<?php

namespace app\classes;


use app\App;
use app\Request;
use app\View;

class FileManagerController
{

    /**
     * @var View|null
     */
    private $view = null;

    public $fileManager = null;


    public function __construct()
    {
        if (is_null($this->view))
            $this->view = new View();
        if (is_null($this->fileManager))
            $this->fileManager = new FileManager();

        $this->init();
    }


    public function run()
    {
        $files = $this->fileManager->getFiles();
        $this->view->files = $files;
        $this->view->display('main');
    }

    private function init()
    {
        $path = Request::get('file');
        $path = filter_var($path, FILTER_SANITIZE_STRING);
        if ($path) {
            if ($path !== App::get('config')->get('main.basePath')) {
                $this->view->backLink = $this->getBackLink($path);
            }
            $this->fileManager->setPath($path);
        }
        if ($this->fileManager->isDir()) {
            $this->fileManager->initFiles();
        } else {
            $this->fileManager->download();
        }
    }

    private function getBackLink(string $path) : string
    {
        $path = trim($path, '/\\');
        $path = str_replace('/', DIRECTORY_SEPARATOR, $path);
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
        $path = mb_substr($path, 0, mb_strrpos($path, DIRECTORY_SEPARATOR));
        return $path;
    }
}