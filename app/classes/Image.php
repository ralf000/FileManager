<?php

namespace app\classes;


use app\App;

class Image extends AFileLeaf
{
    /**
     * @var string иконка
     */
    public $icon = '';

    public function __construct(string $path)
    {
        $this->icon = App::get('config')->get('app.icons.image');
        parent::__construct($path);
    }
}