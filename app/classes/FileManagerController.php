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

    public $backLink = '';


    public function __construct()
    {
        if (is_null($this->view))
            $this->view = new View();
        if (is_null($this->fileManager))
            $this->fileManager = new FileManager();

        $this->handleRequest();
    }

    public function run()
    {
        $files = $this->fileManager->getFiles();
        $this->view->files = $files;
        $this->view->backLink = $this->fileManager->getBackLink();
        $this->view->display('main');
    }

    private function handleRequest()
    {
        if (Request::isPost()) {
            $command = Request::post('command');
            if (isset($command) && $command === 'file-rename') {
                $path = filter_var(Request::post('path'), FILTER_SANITIZE_STRING);
//                $id = filter_var(Request::post('id'), FILTER_SANITIZE_NUMBER_INT);
                $newName = filter_var(Request::post('newName'), FILTER_SANITIZE_STRING);
                if (!$path || !$newName) return false;
//                $file = FileManager::buildFile(new \SplFileInfo($path));
                $file = $this->fileManager->getFile($path);
                $file->rename($newName);
            }
            return true;
        }
    }
    
}