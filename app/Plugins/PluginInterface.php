<?php namespace Prontotype\Plugins;

use Prontotype\Container;

interface PluginInterface
{
    public function __construct($path, Container $container);

    // public function getGlobals();

    // public function getRoutes();

    // public function getCommands();

}