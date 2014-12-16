<?php namespace Prontotype\View\Twig;

use Prontotype\Filesystem\Finder;

class Loader extends \Twig_Loader_Filesystem
{
    public function __construct($paths = array(), $defaultExt = 'twig')
    {
        $this->defaultExt = ltrim($defaultExt, '.');
        parent::__construct($paths);
    }

    public function findTemplate($name)
    {
         if ( strpos($name, ':') === false ) {
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            if ( ! isset($ext) || empty($ext) ) {
                $name = $name . '.' . $this->defaultExt;
            }
            $name = $this->normalizeName($name);
        }

        if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }

        $this->validateName($name);
        
        list($namespace, $shortname) = $this->parseName($name);

        if (!isset($this->paths[$namespace])) {
            throw new \Twig_Error_Loader(sprintf('There are no registered paths for namespace "%s".', $namespace));
        }

        foreach ($this->paths[$namespace] as $path) {
            $finder = new Finder($path);
            $results = $finder->guess($name);
            if ($results->count()) {
                return $this->cache[$name] = $results->first();
            }
        }

        throw new \Twig_Error_Loader(sprintf('Unable to find template "%s".', $name));
    }

    public function getSource($name)
    {
        $template = $this->findTemplate($name);
        return $template->getBody();
    }

}

