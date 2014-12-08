<?php namespace Prontotype\View\Twig;

use Prontotype\View\Finder;

class Loader extends \Twig_Loader_Filesystem
{
    public function __construct($paths = array())
    {
        parent::__construct($paths);
    }

    public function setDefaultExtension($defaultExt)
    {
        $this->defaultExt = ltrim($defaultExt, '.');
    }

    public function findTemplate($name)
    {
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        if ( ! isset($ext) || empty($ext) ) {
            $name = $name . '.' . $this->defaultExt;
        }

        $name = $this->normalizeName($name);

                echo '<pre>';
                print_r($name);
                echo '</pre>';
        
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
            $results = $finder->name(ltrim($name,'/'));
            if ($results->count()) {
                return $this->cache[$name] = $path . '/' . $results->first()->getRelativePathname();
            }
        }

        throw new \Twig_Error_Loader(sprintf('Unable to find template "%s" (looked into: %s).', $name, implode(', ', $this->paths[$namespace])));
    }

    public function getSource($name)
    {
        return file_get_contents($this->findTemplate($name));
    }

}

