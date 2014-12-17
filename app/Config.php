<?php namespace Prontotype;

use Amu\Conph\Config as Conph;

class Config extends Conph {

    protected $basePath;

    protected $srcPath;

    public function convertDataDirectory($value)
    {
        return $this->getPathWithBase($value);
    }

    public function convertTemplatesDirectory($value)
    {
        return $this->getPathWithBase($value);
    }

    public function convertPublicDirectory($value)
    {
        return $this->getPathWithBase($value);
    }

    public function convertCacheDirectory($value)
    {
        return $this->getPathWithBase($value);
    }

    public function convertProntotypeServer($path)
    {
        return $this->getProntotypePath($path);
    }

    public function convertProntotypeTemplates($path)
    {
        return $this->getProntotypePath($path);
    }

    protected function getProntotypePath($path)
    {
        return realpath(make_path($this->get('prontotype.srcpath'), $path));
    }    

    protected function getPathWithBase($path)
    {
        return realpath(make_path($this->get('prontotype.basepath'), $path));
    }
}