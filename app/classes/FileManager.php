<?php
/**
 * Created by PhpStorm.
 * User: kudinov
 * Date: 10.05.2017
 * Time: 16:30
 */

namespace app\classes;


use app\App;
use app\exceptions\FileException;
use app\Request;

class FileManager
{
    /**
     * @var AFile|null
     */
    private $file = null;

    /**
     * FileManager constructor.
     * @param AFile $file
     */
    public function __construct(AFile $file)
    {
        $this->file = $file;
    }

    public function download()
    {

        // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
        // если этого не сделать файл будет читаться в память полностью!
        if (ob_get_level()) {
            ob_end_clean();
        }
        // заставляем браузер показать окно сохранения файла
        header('Content-Description: File Transfer');
        header("Content-Type: {$this->file->getMime()}");
        header('Content-Disposition: attachment; filename=' . $this->file->getName());
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $this->file->getSize());
        // читаем файл и отправляем его пользователю
        readfile($this->file->getPath());
        exit;
    }

    public function upload()
    {
        $maxFileSize = (int)App::get('config')->get('main.maxFileSize');
        if ($this->file->getSize() > $maxFileSize)
            FileException::maxFileSizeExceeded($this->file->getName());

        // Проверяем загружен ли файл
        if(!is_uploaded_file(current(Request::files())['tmp_name']))
            FileException::uploadFileFailure($this->file->getName());

        // Если файл загружен успешно, перемещаем его из временной директории в конечную
        move_uploaded_file(current(Request::files())['tmp_name'], $this->file->getPath());
    }

    public function rename(string $newName) : bool
    {
        $newPath = $this->file->getDirectory() . DIRECTORY_SEPARATOR . $newName;
        if (rename($this->file->getPath(), $newPath)){
            $this->file->setPath($newPath);
        } else {
            FileException::renameFileFailure($this->file->getName());
        }
    }

    public function delete()
    {
        if (!unlink($this->file->getPath()))
            FileException::deleteFileFailure($this->file->getName());
    }

}