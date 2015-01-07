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

    public function isHidden()
    {
        if ( $this->file ) {
            $segments = explode('/', $this->file->getRelativePathname());
            foreach($segments as $segment) {
                if ( strpos($segment, '_') === 0 ) {
                    return true;
                }
            }
            return $this->file->getMetadataValue('hidden');
        }
        return false;
    }

    public function setFileObject(SplFileInfo $fileObj)
    {
        $this->file = $fileObj;
    }

    public function getRelativePathname()
    {
        return $this->file->getRelativePathname();
    }

    public function getMetadata()
    {
        return $this->file->getMetadata();
    }

    public function getBody()
    {
        return $this->file->getBody();
    }

    public function getFilename()
    {
        return $this->file->getBasename();
    }

}