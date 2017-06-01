<?php

namespace app\classes;


use app\App;
use app\helpers\Filter;
use app\helpers\Text;
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
        uasort($files, [self::class, 'sortFiles']);
        $this->view->files = $files;
        $this->view->backLink = $fileManager->getBackLink();
        $this->view->display('main');
    }

    private function sortFiles($a, $b)
    {
        /** @var \SplFileInfo $a */
        /** @var \SplFileInfo $b */
        $aIsComposite = $a instanceof AFileComposite;
        $bIsComposite = $b instanceof AFileComposite;
        if ($aIsComposite && !$bIsComposite) {
            return -1;
        } else if (!$aIsComposite && $bIsComposite) {
            return 1;
        } else {
            return Text::mb_strcasecmp($a->getFilename(), $b->getFilename());
        }
    }

}