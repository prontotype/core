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
    
    public function notFound()
    {
        throw new NotFoundException('Page not found');
    }

    protected function renderTemplate($templatePath, $params = array(), $attr = array())
    {   
        $template = $this->fetchTemplate($templatePath);
        return new Response($template->render($params), 200, $attr);
    }

    protected function downloadTemplate($templatePath, $params = array(), $attr = array())
    {   
        $template = $this->fetchTemplate($templatePath);
        $response = new Response($template->render($params), 200, $attr);
        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $template->getFilename()
        ));
        return $response;
    }

    protected function fetchTemplate($templatePath, $allowHidden = false)
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

    protected function serveStatic($filePath, Request $request)
    {
        if (! file_exists($filePath)) {
            return $this->notFound();
        }
        return new Response(file_get_contents($filePath), 200, [
            'Content-Type' => $request->getRequestMime()
        ]);
    }

    protected function downloadStatic($filePath, Request $request)
    {
        if (! file_exists($filePath)) {
            return $this->notFound();
        }
        $response = new Response(file_get_contents($filePath), 200, [
            'Content-Type' => $request->getRequestMime()
        ]);
        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            pathinfo($filePath, PATHINFO_BASENAME)
        ));
        return $response;
    }
}