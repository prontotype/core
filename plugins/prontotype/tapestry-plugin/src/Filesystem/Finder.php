<?php namespace Prontotype\Plugins\Tapestry\Filesystem;

use Prontotype\Filesystem\Finder as PTFinder;

class Finder extends PTFinder {

    protected $iteratorClass = 'Prontotype\Plugins\Tapestry\Filesystem\RecursiveDirectoryIterator';

    public function isVariant()
    {
        return $this->metadataExists('modifies');
    }

    public function isNotVariant()
    {
        return $this->metadataDoesNotExist('modifies');
    }

    public function hasExtensionIfFile($ext)
    {
        return $this->filter(function($file) use ($ext) {
            if ($file->isDir()) {
                return true;
            }
            return (strtolower($file->getExtension()) === strtolower($ext));
        });
    }

}