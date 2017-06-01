<?php

namespace app\classes;


use app\App;

class File extends AFileLeaf
{
    /**
     * @var string иконка
     */
    public $icon = '';

    public function __construct(string $path)
    {
        $this->icon = App::get('config')->get('app.icons.file');
        parent::__construct($path);
    }
}