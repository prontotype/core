<?php namespace Prontotype\Plugins\Tapestry\Filesystem;

use Prontotype\Filesystem\RecursiveDirectoryIterator as PTRecursiveDirectoryIterator;

class RecursiveDirectoryIterator extends PTRecursiveDirectoryIterator {

    protected $splFileInfoClass = 'Prontotype\Plugins\Tapestry\Filesystem\SplFileInfo';
}