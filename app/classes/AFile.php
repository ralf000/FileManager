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
            header('Location: ' . $this->getPathname());
            exit;
        } else {
            
        }
    }

    public function rename(string $newName)
    {
        $rpos = mb_strrpos($this->getPathname(), $this->getFileNameWithoutExt());
        $length = mb_strlen($this->getFileNameWithoutExt());
        $newPath = substr_replace($this->getPathname(), $newName, $rpos, $length);
        if (!rename($this->getPathname(), $newPath)) {
            FileException::renameFileFailure($this->getFilename());
        }
    }

    public function remove()
    {
        if ($this->isDir()) {
            $result = rmdir($this->getPathname());
        } else {
            $result = unlink($this->getPathname());
        }
        if (!$result)
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
        $extension = strtolower(end(explode('.', $fileName)));
        if (!$extension)
            FileException::invalidExtension($fileName);
        $allowExtensions = App::get('config')->get('main.allowExtensions');

        if (!in_array($extension, $allowExtensions))
            return false;

        return true;
    }

    public function getFileNameWithoutExt() : string
    {
        if ($this->isDir()) {
            return $this->getBasename();
        }
        $extPos = mb_strrpos($this->getBasename(), '.');
        return mb_substr($this->getBasename(), 0, $extPos);
    }


    public function getIcon()
    {
        return $this->icon;
    }
}