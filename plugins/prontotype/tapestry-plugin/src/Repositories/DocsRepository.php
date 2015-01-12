<?php namespace Prontotype\Plugins\Tapestry\Repositories;

use Prontotype\Plugins\Tapestry\Filesystem\SplFileInfo;
use Prontotype\Plugins\Tapestry\Filesystem\Finder;
use Prontotype\Plugins\Tapestry\Entities\DocsEntity;

class DocsRepository extends AbstractRepository
{
    public function findEntity($path, $allowHidden = false)
    {
        
    }

    public function getAll($path = '/', $wrap = true)
    {
        $path = make_path($this->config->get('prontotype.basepath'), $this->config->get('tapestry.docs.directory'), $path);
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

}