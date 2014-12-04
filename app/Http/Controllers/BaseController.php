<?php namespace Prontotype\Http\Controllers;

use Twig_Environment;
use Amu\SuperSharp\Http\Response;
use Prontotype\Http\Request;
use Prontotype\View\TemplateFinder;
use Prontotype\Exception\NotFoundException;

abstract class BaseController {

    public function __construct(TemplateFinder $templates, Twig_Environment $environment)
    {
        $this->templates = $templates;
        $this->environment = $environment;
    }

    public function before(Request $request)
    {
        // add current request into global variables
        $globals = $this->environment->getGlobals();
        $pt = $globals['pt'];
        $pt['request'] = $request;
        $this->environment->addGlobal('pt', $pt);
    }

    public function renderTemplate($templatePath, $params = array(), $attr = array())
    {
        $template = $this->templates->findByPath($templatePath);
        return new Response($template->render($params), 200, $attr);
    }
}