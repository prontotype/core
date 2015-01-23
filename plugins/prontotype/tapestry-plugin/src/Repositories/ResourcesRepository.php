<?php namespace Prontotype\Plugins\Tapestry\Repositories;

use Prontotype\Plugins\Tapestry\Filesystem\SplFileInfo;
use Prontotype\Plugins\Tapestry\Filesystem\Finder;
use Prontotype\Plugins\Tapestry\Entities\ResourceEntity;
use Prontotype\Plugins\Tapestry\Entities\ResourceEntityGroup;
use Prontotype\Exception\NotFoundException;

class ResourcesRepository extends AbstractRepository
{
    protected $groups = null;

    public function getGroups()
    {
        if ( ! is_null($this->groups) ) {
            return $this->groups;
        }
        $groups = $this->config->get('tapestry.resources.groups');
        if ( $groups && count($groups) ) {
            $entityGroups = array();
            foreach($groups as $key => $groupConfig) {
                $entityGroups[$key] = new ResourceEntityGroup($key, $groupConfig, $this);
            }
            $this->groups = $entityGroups;
            return $this->groups;
        }
        return null;
    }

    public function getGroup($group)
    {
        $groups = $this->getGroups();
        if (isset($groups[$group])) {
            return $groups[$group];
        }
        throw new NotFoundException('The resource could not be found.');
    }

    public function findEntity($group, $assetPath)
    {            
        $finder = $this->getAll($group->getPath(), false);
        $result = $finder->pathname($assetPath);
        if ( $result->count()) {
            return $this->wrap($result->first());    
        }   
        throw new NotFoundException('The resource could not be found.');
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