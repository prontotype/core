<?php namespace Prontotype\Plugins\Tapestry;

use Prontotype\Config;

class ViewHelper {

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function url($type, $path, $view = null)
    {
        $url = '/' . make_path($this->config->get('tapestry.' . $type . '.url'), $path);
        if ($view) {
            $url = make_path($url, $view);
        }
        return $url;
    }

    public function markupUrl($path, $view = null)
    {
        return $this->url('markup', $path . '.html', $view);
    }

}