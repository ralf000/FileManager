<?php

namespace app\classes;


use app\App;
use app\exceptions\FileException;
use app\helpers\Text;
use Noodlehaus\Exception\FileNotFoundException;

abstract class AFile extends \SplObjectStorage
{
    /**
     * @var string
     */
    protected $path = '';

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var int
     */
    protected $size = 0;


    public function download()
    {

        // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
        // если этого не сделать файл будет читаться в память полностью!
        if (ob_get_level()) {
            ob_end_clean();
        }
        // заставляем браузер показать окно сохранения файла
        header('Content-Description: File Transfer');
        header("Content-Type: {$this->getMime()}");
        header('Content-Disposition: attachment; filename=' . $this->getName());
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $this->getSize());
        // читаем файл и отправляем его пользователю
        readfile($this->getPath());
        exit;
    }

    public function rename(string $newName) : bool
    {
        $newPath = dirname($this->path) . DIRECTORY_SEPARATOR . $newName;
        if (rename($this->getPath(), $newPath)) {
            $this->setPath($newPath);
        } else {
            FileException::renameFileFailure($this->getName());
        }
    }

    public function remove()
    {
        if (!unlink($this->getPath()))
            FileException::deleteFileFailure($this->getName());
    }

    protected function buildFile(\DirectoryIterator $file) : AFile
    {
        $fileName = $file->getFilename();
        if (!$this->checkExtension($fileName))
            FileException::invalidExtension($fileName);

        if ($file->isDir()) {
            $directory = new Directory($file->getPathname());
            $directory->init();
            return $directory;
        } else {
            $extension = $file->getExtension();
            if (!$extension)
                FileException::invalidExtension($fileName);

            $imgExtensions = App::get('config')->get('main.imgExtensions');
            $fileExtensions = App::get('config')->get('main.fileExtensions');

            $pathName = Text::translit($file->getPathname());
            if (in_array($extension, $imgExtensions)) {
                return new Image($pathName);
            } else if (in_array($extension, $fileExtensions)) {
                return new File($pathName);
            } else {
                FileException::invalidExtension($fileName);
            }
        }
    }

    protected function checkExtension(string $fileName)
    {
        $extension = strtolower(ltrim(strrchr($fileName, '.'), '.'));
        if (!$extension)
            FileException::invalidExtension($fileName);
        $allowExtensions = App::get('config')->get('main.allowExtensions');

        if (!in_array($extension, $allowExtensions))
            return false;

        return true;
    }

    /**
     * @return string
     */
    public function getPath() : string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }


    /**
     * @param string $measure
     * @return int
     */
    public function getSize(string $measure = 'kb') : float
    {
        $size = $this->size;
        switch ($measure) {
            case 'kb': $size = $size / 1000; break;
            case 'mb': $size = round($size / 1000 / 1000, 2); break;
        }
        return $size;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path)
    {
        $this->path = $path;
    }

    public function getIcon()
    {
        return $this->icon;
    }

}