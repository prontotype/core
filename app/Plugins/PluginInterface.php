<?php namespace Prontotype\Plugins;

use Prontotype\Container;

interface PluginInterface
{
    public function __construct($path, Container $container);

    public function register();

    public function getGlobals();

    public function getConfig();

}