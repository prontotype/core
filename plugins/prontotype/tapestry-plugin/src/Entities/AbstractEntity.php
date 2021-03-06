<?php namespace Prontotype\Plugins\Tapestry\Entities;

use Dflydev\ApacheMimeTypes\PhpRepository as MimeRepo;

abstract class AbstractEntity {

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function __call($name, $args)
    {
        if (method_exists($this->file, $name)) {
            return call_user_func_array(array($this->file, $name), $args);
        }
    }

    public function getMimeType()
    {
        $mimes = new MimeRepo();
        return $mimes->findType($this->getExtension());
    }

}