<?php namespace Prontotype\Http;

class ControllerMapper
{
    function getCallback($mapping)
    {
        // check if it's mapping via an ID
        
        // check if it's mapping via a template path

        // check if the mapping is a 'regular' class:method style mapping
        if (preg_match('/^[\\a-zA-Z]+\:[\\a-zA-Z]+$/', $mapping) === 1 ) {
            list($class, $method) = explode(':', $mapping, 2);
            return array($class, $method);
        }
    }
}