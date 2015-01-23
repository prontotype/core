<?php namespace Prontotype\Plugins\Tapestry\Controllers;

use Prontotype\Http\Request;
use Prontotype\Exception\NotFoundException;
use Prontotype\Http\Controllers\BaseController;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class MarkupController extends BaseController
{    
    public function index()
    {
        return $this->renderTemplate('@tapestry/markup/index.twig');
    }

    public function detail($path, Request $request)
    {
        return $this->getResponse($path, 'detail');
    }

    public function preview($path, Request $request)
    {
        return $this->getResponse($path, 'preview');
    }

    public function highlight($path, Request $request)
    {
        return $this->getResponse($path, 'highlight');
    }

    public function raw($path, Request $request)
    {
        return $this->getResponse($path, 'raw', [
            'Content-Type' => 'text/plain'
        ]);
    }

    public function download($path, Request $request)
    {
        $repo = $this->container->make('tapestry.repo.markup');
        $entity = $repo->findEntity($path);
        $response = $this->renderTemplate('@tapestry/markup/download.twig', [
            "template" => $entity,
            "modifiers" => $repo->getModifiersOf($entity)
        ]);
        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $entity->getFilename()
        ));
        return $response;
    }

    protected function getResponse($path, $tpl, $attr = array())
    {
        $repo = $this->container->make('tapestry.repo.markup');
        $entity = $repo->findEntity($path);
        return $this->renderTemplate('@tapestry/markup/' . $tpl . '.twig', [
            "template" => $entity,
            "modifiers" => $repo->getModifiersOf($entity)
        ], $attr);
    }

}