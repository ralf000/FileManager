<?php

namespace app\classes;


use app\exceptions\APIException;
use app\exceptions\FileException;
use app\helpers\Text;
use Noodlehaus\Exception\FileNotFoundException;

abstract class AFileLeaf extends AFile
{
    /**
     * @var string
     */
    protected $directory = '';

    /**
     * @var string
     */
    protected $extension = '';

    /**
     * AFileLeaf constructor.
     * @param string $path
     * @throws FileNotFoundException
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        if(!file_exists($this->path))
            throw new FileNotFoundException("Файл {$this->path} не найден");

        $this->name = basename($path);
        $this->directory = dirname($path);
        $this->size = filesize($path);
        $this->extension = pathinfo($path, PATHINFO_EXTENSION);
    }

    public function remove()
    {
        if (!unlink($this->getPath()))
            FileException::deleteFileFailure($this->getName());
    }

    public function attach($object, $data = null)
    {
        APIException::methodNotAllowed(__CLASS__, __METHOD__);
    }

    public function detach($object)
    {
        APIException::methodNotAllowed(__CLASS__, __METHOD__);
    }

    /**
     * @return string
     */
    public function getDirectory() : string
    {
        return $this->directory;
    }
}