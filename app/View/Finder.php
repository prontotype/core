<?php namespace Prontotype\View;

use Amu\Ffs\Finder as AmuFinder;
use Illuminate\Support\Collection;

class Finder extends AmuFinder {

    public function guess($pathname)
    {
        $idIdent = 'id:';
        if ( strpos($pathname, $idIdent) === 0 ) {
            $id = trim(str_replace($idIdent, '', $pathname));
            return $this->id($id);
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