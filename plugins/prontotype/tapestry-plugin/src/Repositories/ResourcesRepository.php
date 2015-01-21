<?php namespace Prontotype\Plugins\Tapestry\Repositories;

use Prontotype\Plugins\Tapestry\Filesystem\SplFileInfo;
use Prontotype\Plugins\Tapestry\Filesystem\Finder;
use Prontotype\Plugins\Tapestry\Entities\ResourceEntity;
use Prontotype\Plugins\Tapestry\Entities\ResourceEntityGroup;
use Prontotype\Exception\NotFoundException;

class ResourcesRepository extends AbstractRepository
{
    
    public function getGroups()
    {
        $groups = $this->config->get('tapestry.resources.groups');
        if ( $groups && count($groups) ) {
            $entityGroups = array();
            foreach($groups as $key => $groupConfig) {
                $entityGroups[] = new ResourceEntityGroup($key, $groupConfig, $this);
            }
            return $entityGroups;
        }
        return null;
    }

    public function getAll($path = '/', $wrap = true)
    {
        $path = $this->getPath($path);
        try {
            $finder = new Finder($path);
            $result = $finder;
            if ($wrap) {
                return $this->wrap($result);   
            }
            return $result;
        } catch( \Exception $e ) {
            return null;
        }
    }

    protected function wrap($item, $wrapper = 'Prontotype\Plugins\Tapestry\Entities\ResourceEntity')
    {
        if ( $item instanceof Finder) {
            $newArray = array();
            $array = iterator_to_array($item);
            foreach ($array as $iteratorItem) {
                $newArray[] = $this->wrap($iteratorItem);
            }
            return new \ArrayIterator($newArray);
        } else {
            return new ResourceEntity($item);
        }
        return $item;
    }

    protected function getPath($path)
    {
        return make_path($this->config->get('prontotype.basepath'), $path);
    }

}