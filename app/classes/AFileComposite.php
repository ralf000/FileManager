<?php

namespace app\classes;


use app\App;
use app\exceptions\FileException;
use app\Request;

abstract class AFileComposite extends AFile
{

    protected $files = [];


    protected function addFile(\SplFileInfo $file)
    {
        if (!in_array($file, $this->files, true))
            $this->files[] = $file;
    }

    protected function removeFile(\SplFileInfo $file)
    {
        $this->files = array_udiff($this->files, [$file], function ($a, $b) {
            return $a <=> $b;
        });
    }

    public function upload()
    {
        $name = mb_strtolower(Request::file()['name']);
        $tmpName = mb_strtolower(Request::file()['tmp_name']);
        $type = mb_strtolower(Request::file()['type']);
        $size = mb_strtolower(Request::file()['size']);

        if (!$this->checkExtension($name))
            FileException::invalidExtension($name);

        $maxFileSize = (int)App::get('config')->get('main.maxFileSize');
        if ($size > $maxFileSize)
            FileException::maxFileSizeExceeded($name);

        // Проверяем загружен ли файл
        if (!is_uploaded_file($tmpName))
            FileException::uploadFileFailure($name);

        //check double file type (image with comment)
        if (mb_substr_count($type, '/') > 1) {
            FileException::uploadFileFailure($name);
        }

        $maxFileName = App::get('config')->get('main.maxFileName');
        if (mb_strlen($name) > $maxFileName)
            FileException::fileNameLengthExceeded($maxFileSize);

        $name = date('d-m-Y_H:i:s_') . $name;
        // Если файл загружен успешно, перемещаем его из временной директории в конечную
        move_uploaded_file($tmpName, $this->path . DIRECTORY_SEPARATOR . $name);
    }

    public function remove()
    {
        foreach ($this->files as $file) {
            /** @var AFile $file */
            $file->remove();
        }
        parent::remove();
    }

    public function initFiles()
    {
        foreach (new \DirectoryIterator($this->getPathname()) as $file) {
            if ($file->isDot()) continue;

            $file = static::buildFile($file);
            $this->addFile($file);
        }
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    public function getFile(string $path)
    {
        if (in_array($id, $this->files)){
            return $this->files[$id];
        }
        return FileException::fileNotFound();
    }

    /**
     * Возвращает размер всех вложенных файлов
     *
     * @return int
     */
    public function getSize() : int
    {
        $sum = 0;
        foreach ($this->files as $file) {
            /** @var AFile $file */
            $sum += $file->getSize();
        }
        return $sum;
    }

}