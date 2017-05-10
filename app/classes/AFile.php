<?php

namespace app\classes;


use Noodlehaus\Exception\FileNotFoundException;

abstract class AFile
{
    /**
     * @var string
     */
    private $path = '';

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $directory = '';

    /**
     * @var int
     */
    private $size = 0;


    /**
     * AFile constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->checkFile();

        $this->path = $path;
        $this->name = basename($path);
        $this->directory = dirname($path);
        $this->size = filesize($path);
    }

    /**
     * @return mixed
     */
    abstract public function view();

    /**
     * @throws FileNotFoundException
     */
    private function checkFile()
    {
        if (!file_exists($this->path))
            throw new FileNotFoundException("Файл {$this->path} не найден");
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
     * @return string
     */
    public function getDirectory() : string
    {
        return $this->directory;
    }
    

    /**
     * @return int
     */
    public function getSize() : int
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getMime() : string
    {
        return $this->size;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path)
    {
        $this->path = $path;
    }
    

}