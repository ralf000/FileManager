<?php

namespace app\classes;


use app\App;

class FileManager extends AFileComposite
{
    /**
     * @var string
     */
    protected $basePath = '';

    public function __construct()
    {
        $this->basePath = App::get('config')->get('main.basePath');
        parent::__construct($this->basePath);
        $this->init();
    }
}