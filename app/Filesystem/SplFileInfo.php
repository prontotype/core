<?php namespace Prontotype\Filesystem;

use Amu\Ffs\SplFileInfo as AmuFileInfo;

class SplFileInfo extends AmuFileInfo {

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
        if ( $this->isDir() ) {
            return false;
        }
        return ($this->getMetadataValue('hidden') === true);
    }

    public function isNotHidden()
    {
        if ( $this->isDir() ) {
            return true;
        }
        return ($this->getMetadataValue('hidden') !== true);
    }

    public function getTitle()
    {
        return $this->getMetadataValue('title') ?: titlize($this->getBasenameWithoutExtension());
    }

}