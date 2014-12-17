<?php namespace Prontotype\Filesystem;

use Amu\Ffs\Finder as AmuFinder;
use Illuminate\Support\Collection;

class Finder extends AmuFinder {

    protected $iteratorClass = 'Prontotype\Filesystem\RecursiveDirectoryIterator';

    public function guess($pathname)
    {
        if ( strpos($pathname, ':') !== false ) {
            list($key, $value) = explode(':', $pathname);
            $method = $key . 'Equals';
            return $this->$method($value);
        }
        return $this->pathname($pathname);
    }

    public function id($id)
    {
        return $this->idEquals($id);
    }

    public function hidden()
    {
        return $this->hiddenEquals(true);
    }

    public function notHidden()
    {
        return $this->hiddenDoesNotEqual(true);
    }

    public function all()
    {
        return new Collection($this->toArray());
    }

    public function first()
    {
        $items = $this->toArray();
        return $items[0];
    }

    public function toArray()
    {
        return array_values(iterator_to_array($this));
    }

}