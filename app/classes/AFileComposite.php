<?php

namespace app\classes;


use app\App;
use app\exceptions\FileException;
use app\Request;

abstract class AFileComposite extends AFile
{

    /**
     * AFile constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $this->path = $path;
        $this->name = basename($path);
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
        foreach ($this as $file) {
            /** @var AFile $file */
            $file->remove();
        }
        parent::remove();
    }

    protected function init()
    {
        foreach (new \DirectoryIterator($this->path) as $file) {
            if ($file->isDot()) continue;

            $file = $this->buildFile($file);
            $this->attach($file);
        }
    }

    /**
     * @param string $name
     * @return AFile
     */
    public function getFile(string $name) : AFile
    {
        foreach ($this as $file) {
            /** @var AFile $file */
            if ($file->getName() === $name) {
                return $file;
            }
        }
        return null;
    }

    public function getFiles()
    {
        $files = [];
        foreach ($this as $file) {
            $files[] = $file;
        }
        return $files;
    }

    /**
     * Возвращает размер всех вложенных файлов
     *
     * @return int
     */
    public function getSize() : int
    {
        $sum = 0;
        foreach ($this as $file) {
            /** @var AFile $file */
            $sum += $file->getSize();
        }
        return $sum;
    }


}