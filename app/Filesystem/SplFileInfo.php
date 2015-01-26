<?php namespace Prontotype\Filesystem;

use Amu\Ffs\SplFileInfo as AmuFileInfo;
use Dflydev\ApacheMimeTypes\PhpRepository as MimeRepo;

class SplFileInfo extends AmuFileInfo
{
    protected $parse = ['html','twig','md','markdown'];

    protected $mimeType = null;

    protected $mimeChecked = false;

    public function getMetadata()
    {
        if (in_array(strtolower($this->getExtension()), $this->parse)) {
            return parent::getMetadata();
        }
        return null;
    }

    public function getBody()
    {
        if (in_array(strtolower($this->getExtension()), $this->parse)) {
            return parent::getBody();
        }
        return parent::getContents();
    }

    public function getId()
    {
        return $this->getMetadataValue('id');
    }

    public function metadataDoesNotExist($key)
    {
        if ( $this->isDir() ) {
            return true;
        }
        $metadata = $this->getMetadata();
        return ! isset($metadata[$key]);
    }

    public function isHidden()
    {
        $segments = explode('/', $this->getRelativePathname());
        foreach($segments as $segment) {
            if ( strpos($segment, '_') === 0 ) {
                return true;
            }
        }
        return ($this->getMetadataValue('hidden') === true);
    }

    public function isNotHidden()
    {
        return ! $this->isHidden();
    }

    public function getTitle()
    {
        return $this->getMetadataValue('title') ?: titlize(preg_replace('/^([\d]*\-)/', '', $this->getBasenameWithoutExtension()));
    }

    public function getRelativePathname($search = null, $replace = null)
    {
        if ( $search && $replace ) {
            return str_replace($search, $replace, parent::getRelativePathname());
        }
        return parent::getRelativePathname();
    }

    public function getUrlPath()
    {
        return preg_replace('/' . '.' . $this->getExtension() . '$/', '', $this->getRelativePathname());
    }

    public function getMimeType()
    {
        if (! $this->mimeChecked) {
            $mimes = new MimeRepo();
            $this->mimeType = $mimes->findType($this->getExtension());
        }
        return $this->mimeType;
    }
}