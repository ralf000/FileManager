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

    public function __construct()
    {
        $this->basePath = App::get('config')->get('main.basePath');
        parent::__construct($this->basePath);

        $this->init();
    }

}