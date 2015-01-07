<?php namespace Prontotype\View\Twig;

class Environment extends \Twig_Environment
{
    public function loadTemplate($name, $index = null)
    {
        if (is_array($name) ) {
            foreach($name as $tpl) {
                try {
                    return $this->doLoadTemplate($tpl);
                } catch( \Twig_Error_Loader $e ) {}    
            }
            throw new \Twig_Error_Loader('Unable to find templates ' . implode(', ', $name));
        } else {
            return $this->doLoadTemplate($name, $index);
        }
    }

    protected function doLoadTemplate($name, $index = null)
    {
        $cls = $this->getTemplateClass($name, $index);
                
        if (isset($this->loadedTemplates[$cls])) {
            return $this->loadedTemplates[$cls];
        }

        if (!class_exists($cls, false)) {
                    
            $tpl = $this->getLoader()->findTemplate($name);

            if (false === $cache = $this->getCacheFilename($name)) {
                eval('?>'.$this->compileSource($tpl->getBody(), $name));
            } else {
                if (!is_file($cache) || ($this->isAutoReload() && !$this->isTemplateFresh($name, filemtime($cache)))) {
                    $this->writeCacheFile($cache, $this->compileSource($tpl->getBody(), $name));
                }

                require_once $cache;
            }
        }

        if (!$this->runtimeInitialized) {
            $this->initRuntime();
        }
        
        $instance = new $cls($this);

        $instance->setFileObject($tpl);

        return $this->loadedTemplates[$cls] = $instance;
    }

}