<?php namespace Prontotype\Filesystem;

use Amu\Ffs\Iterator\RecursiveDirectoryIterator as RDI;

class RecursiveDirectoryIterator extends RDI {

    protected $splFileInfoClass = 'Prontotype\Filesystem\SplFileInfo';
}