<?php namespace Prontotype\Plugins\Tapestry\Controllers;

use Prontotype\Http\Request;
use Prontotype\Exception\NotFoundException;
use Prontotype\Http\Controllers\BaseController;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DocsController extends BaseController
{   
    
    public function index()
    {
        $repo = $this->container->make('tapestry.repo.docs');
        return $this->renderTemplate('@tapestry/docs/index.twig', [
            "page" => $repo->getAll()
        ]);
    }

    public function page($path, Request $request)
    {
        return $this->getResponse($path, 'page');
    }

    protected function getResponse($path, $tpl, $attr = array())
    {
        $repo = $this->container->make('tapestry.repo.docs');
        return $this->renderTemplate('@tapestry/docs/' . $tpl . '.twig', [
            "page" => $repo->findEntity($path)
        ], $attr);
    }

}