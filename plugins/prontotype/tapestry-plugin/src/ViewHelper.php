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

    public function generateUrl($type, $item, $opt = null, $opt2 = null)
    {
        if ($type == 'markup') {
            return $this->markupUrl($item->getUrlPath(), $opt);
        } elseif ($type == 'docs') {
            return $this->docsUrl($item->getUrlPath());
        } elseif ($type == 'resources') {
            return $this->resourcesUrl($item->getRelativePathname(), $opt->getKey(), $opt2);
        } else {
            return '#';
        }
    }

    public function markupUrl($path, $view = null)
    {
        return $this->url('markup', $path . '.html', $view);
    }

    public function docsUrl($path)
    {
        return $this->url('docs', $path);
    }

    public function resourcesUrl($path, $groupPath, $view = null)
    {
        return $this->url('resources', make_path($groupPath, $path), $view);
    }

}