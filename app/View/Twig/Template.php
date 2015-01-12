<?php namespace Prontotype\View\Twig;

use Twig_Template, Twig_TemplateInterface;
use Amu\Ffs\SplFileInfo;
use Dflydev\ApacheMimeTypes\PhpRepository as MimeRepo;

abstract class Template extends Twig_Template
{
    protected $file = null;

    public function getMimeType()
    {
        $ext = strtolower(pathinfo($this->getTemplateName(), PATHINFO_EXTENSION));
        $mimes = new MimeRepo();
        return $mimes->findType($ext);
    }

    public function render(array $context = array())
    {
        return parent::render($context);
    }

    public function setFileObject(SplFileInfo $fileObj)
    {
        $this->file = $fileObj;
    }

    public function getFilename()
    {
        return $this->file->getBasename();
    }

    public function __call($name, $args)
    {
        if (method_exists($this->file, $name)) {
            return call_user_func_array(array($this->file, $name), $args);
        }
    }

    public function getFile()
    {
        return $this->file;
    }

}