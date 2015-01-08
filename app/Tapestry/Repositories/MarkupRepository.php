<?php namespace Prontotype\Tapestry\Repositories;

use Prontotype\Tapestry\Filesystem\SplFileInfo;
use Prontotype\Tapestry\Filesystem\Finder;

class MarkupRepository extends AbstractRepository {

    public function getAll($path = '/')
    {
        $path = make_path($this->config->get('templates.directory'), $path);
        $finder = $this->newFinder($path);
        return $finder->notHidden()->isNotVariant()->hasExtensionIfFile($this->config->get('templates.extension'));
    }

    public function getVariantsOf(SplFileInfo $file)
    {
        
    }

    public function newFinder($path)
    {
        return new Finder($path);
    }

}