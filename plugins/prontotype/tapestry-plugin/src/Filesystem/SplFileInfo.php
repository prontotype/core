<?php namespace Prontotype\Plugins\Tapestry\Filesystem;

use Prontotype\Filesystem\SplFileInfo as PTSplFileInfo;

class SplFileInfo extends PTSplFileInfo
{
    protected $previewTypes = [
        'image' => ['png', 'jpg', 'jpeg', 'gif', 'svg'],
        'video' => ['mpeg'],
        'code' => ['js', 'json', 'css', 'scss', 'coffee', 'less', 'md', 'markdown']
    ];

    public function getPreviewableExtensions()
    {
        $exts = [];
        foreach($this->previewTypes as $type) {
            $exts = array_merge($exts, $type);
        }
        return $exts;
    }

    public function isPreviewable()
    {
        return in_array(strtolower($this->getExtension()), $this->getPreviewableExtensions());
    }

    public function previewType()
    {
        foreach($this->previewTypes as $type => $exts) {
            if ( in_array(strtolower($this->getExtension()), $exts) ) {
                return $type;
            }
        }
        return null;
    }

}