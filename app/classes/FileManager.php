<?php

namespace app\classes;


use app\App;
use app\View;

class FileManager extends AFileComposite
{
    /**
     * @var string
     */
    private $basePath = '';

    /**
     * @var View|null
     */
    private $view  = null;

    public function __construct()
    {
        $this->basePath = App::get('config')->get('main.basePath');
        parent::__construct($this->basePath);

        if (is_null($this->view))
            $this->view = new View();

        $this->init();

        $this->view->display('main');
    }

}