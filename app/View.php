<?php

namespace app;


class View
{
    /**
     * @var string путь до папки views
     */
    private $viewPath = '';
    /**
     * @var string шаблон приложения
     */
    private $layout = '';


    public function __construct()
    {
        if (empty($this->viewPath)) {
            $this->viewPath = App::get('config')->get('app.viewPath');
        }
        if (empty($this->layout)) {
            $this->layout = App::get('config')->get('app.layout');
        }
    }

    /**
     * @param string $view
     */
    public function display(string $view)
    {
        echo $this->render($this->viewPath . DIRECTORY_SEPARATOR . $view . '.php');
    }

    /**
     * @param string $view
     * @return string
     */
    private function render(string $view) : string
    {
        ob_start();
        require_once $this->getLayoutPath()
            . DIRECTORY_SEPARATOR
            . 'index.php';
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    /**
     * @return string
     */
    private function getLayoutPath() : string
    {
        return $this->viewPath
        . DIRECTORY_SEPARATOR
        . 'layouts'
        . DIRECTORY_SEPARATOR
        . $this->layout;
    }

    /**
     * @return string
     */
    private function getLayoutChunks() : string
    {
        return $this->getLayoutPath() . DIRECTORY_SEPARATOR . 'chunks';
    }

}