<?php namespace Prontotype\Http\Controllers;

use Twig_Error_Loader;
use Amu\SuperSharp\Http\Response;
use Prontotype\Http\Request;
use Prontotype\View\TemplateFinder;
use Prontotype\Exception\NotFoundException;

abstract class BaseController {

    public function __construct(TemplateFinder $templates)
    {
        $this->templates = $templates;
    }

    public function render($templatePath, $params, Request $request)
    {
        $template = $this->templates->findByPath($templatePath);
        $environment = $template->getEnvironment();
        $globals = $environment->getGlobals();
        $pt = $globals['pt'];
        $pt['request'] = $request;
        $environment->addGlobal('pt', $pt);
        return new Response($template->render($params), 200, [
            'Content-Type' => $request->getRequestMime()
        ]);
    }
}