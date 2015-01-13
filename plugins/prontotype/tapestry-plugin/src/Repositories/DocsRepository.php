<?php namespace Prontotype\Plugins\Tapestry\Repositories;

use Prontotype\Plugins\Tapestry\Filesystem\SplFileInfo;
use Prontotype\Plugins\Tapestry\Filesystem\Finder;
use Prontotype\Plugins\Tapestry\Entities\DocsEntity;
use Prontotype\Exception\NotFoundException;

class DocsRepository extends AbstractRepository
{
    public function findIndexEntity()
    {
        $finder = $this->getAll('/', false);
        $result = $finder->indexEquals(true);
        // $result = $finder->pathname($path . '.' . $this->config->get('tapestry.docs.extension'));
        if ( $result->count()) {
            return $this->wrap($result->first());    
        }
        throw new NotFoundException('The documentation page could not be found.');
    }

    public function findEntity($path)
    {
        $finder = $this->getAll('/', false);
        $result = $finder->pathname($path . '.' . $this->config->get('tapestry.docs.extension'));
        if ( $result->count()) {
            return $this->wrap($result->first());    
        }
        throw new NotFoundException('The documentation page could not be found.');
    }

    public function getAll($path = '/', $wrap = true)
    {
        if ( ! $path = $this->getPath($path) ) {
            return null;
        }
        $finder = new Finder($path);
        $result = $finder->notHidden()->hasExtensionIfFile($this->config->get('tapestry.docs.extension'));
        if ($wrap) {
            return $this->wrap($result);   
        }
        return $result;
    }

    protected function wrap($item)
    {
        if ( $item instanceof Finder) {
            $newArray = array();
            $array = iterator_to_array($item);
            foreach ($array as $iteratorItem) {
                $newArray[] = $this->wrap($iteratorItem);
            }
            return new \ArrayIterator($newArray);
        } else {
            return new DocsEntity($item, $this->twig);
        }
        return $item;
    }

    protected function getPath($path)
    {
        $path = make_path($this->config->get('prontotype.basepath'), $this->config->get('tapestry.docs.directory'), $path);
        if (!file_exists($path)) {
            return null;
        }
        return $path;
    }

}