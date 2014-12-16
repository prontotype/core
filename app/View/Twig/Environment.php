<?php namespace Prontotype\View\Twig;

class Environment extends \Twig_Environment
{
    public function loadTemplate($name, $index = null)
    {
        if (is_array($name) ) {
            foreach($name as $tpl) {
                try {
                    return $this->loadTemplate($tpl);
                } catch( \Twig_Error_Loader $e ) {}    
            }
            throw new \Twig_Error_Loader('Unable to find template');
        } else {
            return $this->loadTemplate($name, $index);
        }
    }

    protected function doLoadTemplate($name, $index = null)
    {
        $cls = $this->getTemplateClass($name, $index);
        
        if (isset($this->loadedTemplates[$cls])) {
            return $this->loadedTemplates[$cls];
        }

        if (!class_exists($cls, false)) {
            if (false === $cache = $this->getCacheFilename($name)) {
                eval('?>'.$this->compileSource($this->getLoader()->getSource($name), $name));
            } else {
                if (!is_file($cache) || ($this->isAutoReload() && !$this->isTemplateFresh($name, filemtime($cache)))) {
                    $this->writeCacheFile($cache, $this->compileSource($this->getLoader()->getSource($name), $name));
                }

                require_once $cache;
            }
        }

        if (!$this->runtimeInitialized) {
            $this->initRuntime();
        }
        
        return $this->loadedTemplates[$cls] = new $cls($this);
    }

}