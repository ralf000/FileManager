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
        if (file_exists($this->path)) {
            header('X-SendFile: ' . realpath($this->path));
            header('Content-Type: ' . mime_content_type($this->path));
            header('Content-Disposition: attachment; filename=' . basename($this->path));
            exit;
        } else {
            //TODO 404 error redirect
        }
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

    public function isDir(string $path = '') : bool
    {
        if (!$path)
            $path = $this->path;
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        return ($extension == false);
    }


    protected function buildFile(\SplFileInfo $file) : AFile
    {
        $fileName = $file->getFilename();
        if (!$this->checkExtension($fileName))
            FileException::invalidExtension($fileName);

        if ($file->isDir()) {
            $directory = new Directory($file->getPathname());
            $directory->initFiles();
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
     * @param string $path
     * @return string
     */
    private function getFileName(string $path) : string
    {
        return pathinfo($path, PATHINFO_FILENAME);
    }

    /**
     * @param string $path
     * @return string
     */
    private function getExtension(string $path) : string
    {
        return pathinfo($path, PATHINFO_EXTENSION);
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
     * @return float
     */
    public function getSize(string $measure = 'kb') : float
    {
        $size = $this->size;
        switch ($measure) {
            case 'kb':
                $size = $size / 1000;
                break;
            case 'mb':
                $size = round($size / 1000 / 1000, 2);
                break;
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