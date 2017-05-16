<?php

namespace app\classes;


use app\View;

class FileManagerController
{

    /**
     * @var View|null
     */
    private $view  = null;


    public function __construct()
    {
        if (is_null($this->view))
            $this->view = new View();
    }


    public function run()
    {
        $fileManager = new FileManager();
        $files = $fileManager->getFiles();
        $this->view->files = $files;
        $this->view->display('main');
    }
}