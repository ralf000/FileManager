<?php

namespace app\classes;


use app\App;
use app\exceptions\FileException;
use app\helpers\Text;
use Noodlehaus\Exception\FileNotFoundException;

abstract class AFile extends \SplFileInfo
{

    public function download()
    {
        if (file_exists($this->getPathname())) {
            header('X-SendFile: ' . realpath($this->getRealPath()));
            header('Content-Type: ' . mime_content_type($this->getPathname()));
            header('Content-Disposition: attachment; filename=' . basename($this->getPathname()));
            exit;
        } else {
            //TODO 404 error redirect
        }
    }

    public function rename(string $newName) : bool
    {
        $newPath = $this->getRealPath() . DIRECTORY_SEPARATOR . $newName;
        if (rename($this->getPath(), $newPath)) {
            $this->file = new static($newPath);
        } else {
            FileException::renameFileFailure($this->getFilename());
        }
    }

    public function remove()
    {
        if (!unlink($this->getPath()))
            FileException::deleteFileFailure($this->getFilename());
    }
    


    public static function buildFile(\SplFileInfo $file) : AFile
    {
        if ($file->isDir()) {
            $directory = new Directory($file->getPathname());
            $directory->initFiles();
            return $directory;
        } else {
            $extension = $file->getExtension();
            if (!$extension)
                FileException::invalidExtension($file->getFilename());

            $imgExtensions = App::get('config')->get('main.imgExtensions');
            $fileExtensions = App::get('config')->get('main.fileExtensions');

            $pathName = Text::translit($file->getPathname());
            if (in_array($extension, $imgExtensions)) {
                return new Image($pathName);
            } else if (in_array($extension, $fileExtensions)) {
                return new File($pathName);
            } else {
                FileException::invalidExtension($file->getFilename());
            }
        }
    }

    protected function checkExtension(string $fileName)
    {
        $extension = strtolower($this->getExtension());
        if (!$extension)
            FileException::invalidExtension($fileName);
        $allowExtensions = App::get('config')->get('main.allowExtensions');

        if (!in_array($extension, $allowExtensions))
            return false;

        return true;
    }
    

    public function getIcon()
    {
        return $this->icon;
    }

}