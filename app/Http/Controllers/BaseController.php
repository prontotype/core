<?php namespace Prontotype\Http\Controllers;

use Prontotype\Container;
use Amu\SuperSharp\Http\Response;
use Prontotype\Http\Request;
use Prontotype\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

abstract class BaseController {

    public function __construct(Container $container)
    {
        $this->twig = $container->make('prontotype.view.environment');
        $this->globals = $container->make('prontotype.view.globals');
        $this->config = $container->make('prontotype.config');
        $this->container = $container;
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

    public function downloadTemplate($templatePath, $params = array(), $attr = array())
    {   
        $template = $this->fetchTemplate($templatePath);
        $response = new Response($template->render($params), 200, $attr);
        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $template->getFilename()
        ));
        return $response;
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