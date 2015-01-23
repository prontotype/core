<?php namespace Prontotype\Plugins\Tapestry\Entities;

use Prontotype\View\Twig\Environment;

class ResourceEntityGroup {

    public function __construct($key, $config, $repo)
    {
        $this->key = $key;
        $this->config = $config;
        $this->repo = $repo;
    }

    public function getTitle()
    {
        return isset($this->config['title']) ? $this->config['title'] : titlize(preg_replace('/^([\d]*\-)/', '', $key));
    }

    public function getPath()
    {
        return $this->config['directory'];
    }

    public function getKey()
    {
        return $this->key;
    }

}