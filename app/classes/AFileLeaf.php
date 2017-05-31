<?php

namespace app\classes;


use app\exceptions\APIException;
use app\exceptions\FileException;
use app\helpers\Text;
use Noodlehaus\Exception\FileNotFoundException;

abstract class AFileLeaf extends AFile
{

    /*public function addFile(\SplFileInfo $file)
    {
        APIException::methodNotAllowed(__CLASS__, __METHOD__);
    }

    public function removeFile(\SplFileInfo $file)
    {
        APIException::methodNotAllowed(__CLASS__, __METHOD__);
    }*/
}