<?php namespace Prontotype\View;

class Globals {

    protected $globals = array();

    public function add($key, $value)
    {
        $this->globals[$key] = $value;
    }

    public function all()
    {
        return $this->globals;
    }

    public function merge($items)
    {
        $this->globals = merge($globals, $items);
    }
    
}