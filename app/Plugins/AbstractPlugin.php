<?php namespace Prontotype\Plugins;

use Prontotype\Container;
use Prontotype\Http\Application as Http;
use Prontotype\Console\Application as Console;

abstract class AbstractPlugin
{
    public function __construct($path, Container $container)
    {
        $this->container = $container;
        $this->path = $path;
    }

    public function getConfig()
    {
        return array();
    }

    public function getGlobals()
    {
        return array();
    }

    public function registerRoutes(Http $app)
    {
        
    }

    public function registerCommands(Console $app)
    {
        
    }

}