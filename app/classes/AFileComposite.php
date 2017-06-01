<?php

namespace app\classes;


use app\App;
use app\exceptions\FileException;
use app\helpers\Text;
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
        $path = Request::get('path') ?? App::get('config')->get('main.basePath');
        $files = Request::files();
        $files['name'] = array_map('mb_strtolower', $files['name']);

        $tmpFunction = function ($element) use ($path) {
            return $path . DIRECTORY_SEPARATOR . time() . '_' . Text::translit($element);
        };
        $files = array_combine($files['tmp_name'], array_map($tmpFunction, $files['name']));

        foreach ($files as $tmpName => $name) {
            $this->checkUploadedFile($tmpName, $name);
            // Если файл загружен успешно, перемещаем его из временной директории в конечную
            move_uploaded_file($tmpName, $name);
        }
        header('Location: /?path=' . $path);
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

    public function getFile(string $id)
    {
        return ($this->files[$id]) ?? FileException::fileNotFound();
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

    private function checkUploadedFile(string $tmpName, string $name)
    {
        if (!$this->checkExtension($name))
            FileException::invalidExtension($name);

        $maxFileSize = (int)App::get('config')->get('main.maxFileSize');
        if (filesize($tmpName) > $maxFileSize)
            FileException::maxFileSizeExceeded($name);

        // Проверяем загружен ли файл
        if (!is_uploaded_file($tmpName))
            FileException::uploadFileFailure($name);

        //check double file type (image with comment)
        if (mb_substr_count(mime_content_type($tmpName), '/') > 1) {
            FileException::uploadFileFailure($name);
        }

        $maxFileName = App::get('config')->get('main.maxFileName');
        if (mb_strlen($name) > $maxFileName)
            FileException::fileNameLengthExceeded($maxFileSize);
    }

}