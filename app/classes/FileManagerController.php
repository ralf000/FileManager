<?php

namespace app\classes;


use app\App;
use app\helpers\Filter;
use app\Request;
use app\View;

class FileManagerController
{
    /**
     * @var View|null
     */
    private $view = null;

    public $backLink = '';


    public function __construct()
    {
        if (is_null($this->view))
            $this->view = new View();
    }

    public function run()
    {
        $fileManager = App::get('fileManager');
        $files = $fileManager->getFiles();
        $this->view->files = $files;
        $this->view->backLink = $fileManager->getBackLink();
        $this->view->display('main');
    }

}