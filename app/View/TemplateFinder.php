<?php namespace Prontotype\View;

use Twig_Environment, Twig_Error_Loader;
use Prontotype\Config;
use Prontotype\Exception\NotFoundException;

class TemplateFinder {

    public function __construct(Twig_Environment $environment, Config $config)
    {
        $this->environment = $environment;
        $this->config = $config;
    }

    public function findByPath($path)
    {
        if ( $path !== '/' ) {
            try {
                return $this->environment->loadTemplate($path);
            } catch(Twig_Error_Loader $e) {
                if (pathinfo($path, PATHINFO_EXTENSION)) {
                    throw new NotFoundException('Template not found');
                }
            }
        }
        try {
            return $this->environment->loadTemplate(make_path($path, 'index'));
        } catch(Twig_Error_Loader $e) {
            throw new NotFoundException('Template not found');
        }
    }

    public function findNotFoundTemplate()
    {
        return $this->findByPath($this->config->get('templates.notfound'));
    }

    public function findErrorTemplate()
    {
        return $this->findByPath($this->config->get('templates.error'));
    }

}