<?php

namespace app\classes;


use app\App;

class Directory extends AFileComposite
{
    /**
     * @var string иконка
     */
    public $icon = '';

    public function __construct(string $path)
    {
        $this->icon = App::get('config')->get('app.icons.folder');
        parent::__construct($path);
    }


}