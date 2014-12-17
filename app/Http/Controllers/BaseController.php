<?php namespace Prontotype\Http\Controllers;

use Prontotype\View\Twig\Environment;
use Amu\SuperSharp\Http\Response;
use Prontotype\Http\Request;
use Prontotype\Config;
use Prontotype\Exception\NotFoundException;

abstract class BaseController {

    public function __construct(Environment $twig, Config $config)
    {
        $this->twig = $twig;
        $this->config = $config;
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
        $template = $this->fetchTemplate($templatePath);
        return new Response($template->render($params), 200, $attr);
    }

    public function fetchTemplate($templatePath, $allowHidden = false)
    {
        try {
            $template = $this->twig->loadTemplate(array(
                $templatePath,
                rtrim($templatePath,'/') . '/index'
            ));
            if ( ! $allowHidden && $template->isHidden() ) {
                throw new NotFoundException('Page not found');
            }
            return $template;
        } catch (\Twig_Error_Loader $e) {
            throw new NotFoundException('Page not found');
        }
    }
}