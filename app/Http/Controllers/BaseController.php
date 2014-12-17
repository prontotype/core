<?php namespace Prontotype\Http\Controllers;

use Prontotype\View\Twig\Environment;
use Amu\SuperSharp\Http\Response;
use Prontotype\Http\Request;
use Prontotype\View\Twig\Loader;
use Prontotype\Exception\NotFoundException;

abstract class BaseController {

    public function __construct(Loader $loader, Environment $twig)
    {
        $this->loader = $loader;
        $this->twig = $twig;
    }

    public function before(Request $request)
    {
        // add current request into global variables
        $globals = $this->twig->getGlobals();
        $pt = $globals['pt'];
        $pt['request'] = $request;
        $this->twig->addGlobal('pt', $pt);
    }

    public function renderTemplate($templatePath, $params = array(), $attr = array())
    {   
        try {
            $template = $this->twig->loadTemplate(array(
                $templatePath,
                rtrim($templatePath,'/') . '/index'
            ));
            if ( $template->isHidden() ) {
                throw new NotFoundException('Page not found');
            }
            return new Response($template->render($params), 200, $attr);
        } catch (\Twig_Error_Loader $e) {
            throw new NotFoundException('Page not found');
        }
    }
}