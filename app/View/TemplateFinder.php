<?php namespace Prontotype\View;

use Twig_Environment;

class TemplateFinder {

    public function __construct(Twig_Environment $environment)
    {
        $this->environment = $environment;
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

}