<?php namespace Prontotype\Plugins\Tapestry\Filesystem;

use Prontotype\Filesystem\SplFileInfo as PTSplFileInfo;

class SplFileInfo extends PTSplFileInfo
{
    protected $highlightable = ['js', 'json', 'css', 'scss', 'coffee', 'less', 'md', 'markdown'];

    public function isHighlightable()
    {
        return in_array(strtolower($this->getExtension()), $this->highlightable);
    }


}