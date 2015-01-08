<?php namespace Prontotype\Tapestry\Filesystem;

use Prontotype\Filesystem\Finder as PTFinder;

class Finder extends PTFinder {

    public function isVariant()
    {
        return $this->metadataExists('extends');
    }

    public function isNotVariant()
    {
        return $this->metadataDoesNotExist('extends');
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