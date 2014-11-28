<?php namespace Prontotype\Http\Controllers;

use Twig_Error_Loader;
use Twig_Environment;
use Prontotype\Exception\NotFoundException;

abstract class BaseController {

    public function __construct(Twig_Environment $view)
    {
        $this->view = $view;
    }

    public function findViewTemplateByUrl($url)
    {
        if ( $url !== '/' ) {
            try {
                return $this->view->loadTemplate($url);
            } catch(Twig_Error_Loader $e) {
                if (pathinfo($url, PATHINFO_EXTENSION)) {
                    throw new NotFoundException('Template not found');
                }
            }
        }
        try {
            return $this->view->loadTemplate(make_path($url, 'index'));
        } catch(Twig_Error_Loader $e) {
            throw new NotFoundException('Template not found');
        }
    }

}