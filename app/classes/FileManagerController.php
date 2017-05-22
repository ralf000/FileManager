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

    /**
     * @var FileManager
     */
    public $fileManager = null;



    public function __construct()
    {
        if (is_null($this->view))
            $this->view = new View();
        if (is_null($this->fileManager))
            $this->fileManager = new FileManager();

        $this->handleRequest();

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
        $this->initPath();
        if ($this->fileManager->isDir()) {
        /** @var AFileComposite $file */
            $this->fileManager->initFiles();
        } else {
        /** @var AFileLeaf $file */
            $this->fileManager->download();
        }
    }

    private function initPath()
    {
        $path = Request::get('path');
        if (!$path) return;

        $path = $this->handlePath($path);

        $basePath = App::get('config')->get('main.basePath');
        if (mb_strpos($path, $basePath) === false)
            return;

        if ($path !== $basePath) {
            $this->view->backLink = $this->getBackLink($path);
        }
        $this->fileManager = new FileManager($path);
    }

    private function handlePath(string $path) : string
    {
        $path = filter_var($path, FILTER_SANITIZE_STRING);
        $path = trim($path, '/\\');
        $path = str_replace('/', DIRECTORY_SEPARATOR, $path);
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);

        return $path;
    }

    private function handleRequest()
    {
        if (Request::isPost()) {
            $command = Request::post('command');
            if (isset($command) && $command === 'file-rename') {
                $path = Request::post('path');
                $newName = Request::post('newName');
                if (!$path || !$newName) return false;
                $file = (is_dir($path)) ? new Directory($path) : new File($path);
                $file->rename($newName);
            }
            return true;
        }
    }

    private function getBackLink(string $path) : string
    {
        $path = mb_substr($path, 0, mb_strrpos($path, DIRECTORY_SEPARATOR));
        return $path;
    }
}