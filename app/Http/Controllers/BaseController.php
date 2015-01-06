<?php namespace Prontotype\Http\Controllers;

use Prontotype\View\Twig\Environment;
use Amu\SuperSharp\Http\Response;
use Prontotype\Http\Request;
use Prontotype\Config;
use Prontotype\View\Globals;
use Prontotype\Exception\NotFoundException;

abstract class BaseController {

    public function __construct(Environment $twig, Config $config, Globals $globals)
    {
        $this->twig = $twig;
        $this->config = $config;
        $this->globals = $globals;
    }

    public function before(Request $request)
    {
        $this->globals->add('request', $request);
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
                $this->notFound();
            }
            return $template;
        } catch (\Twig_Error_Loader $e) {
            $this->notFound();
        }
    }

    public function notFound()
    {
        throw new NotFoundException('Page not found');
    }
}