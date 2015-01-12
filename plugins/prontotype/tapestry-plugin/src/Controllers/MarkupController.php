<?php namespace Prontotype\Plugins\Tapestry\Controllers;

use Prontotype\Http\Request;
use Prontotype\Exception\NotFoundException;
use Prontotype\Http\Controllers\BaseController;

class MarkupController extends BaseController
{    
    public function detail($path, Request $request)
    {
        $entity = $this->container->make('tapestry.repo.markup')->findEntity($path);
        return $this->renderTemplate('@tapestry/markup/detail.twig', [
            "template" => $entity
        ]);
    }

    public function preview($path, Request $request)
    {
        $entity = $this->container->make('tapestry.repo.markup')->findEntity($path);
        return $this->renderTemplate('@tapestry/markup/preview.twig', [
            "template" => $entity
        ]);
    }

    public function highlight($path, Request $request)
    {
        $entity = $this->container->make('tapestry.repo.markup')->findEntity($path);
        return $this->renderTemplate('@tapestry/markup/highlight.twig', [
            "template" => $entity
        ]);
    }

    public function raw($path, Request $request)
    {
        $entity = $this->container->make('tapestry.repo.markup')->findEntity($path);
        return $this->renderTemplate('@tapestry/markup/raw.twig', [
            "template" => $entity
        ],[
            'Content-Type' => 'text/plain'
        ]);
    }

    public function download($path, Request $request)
    {
        return $this->downloadTemplate($path);
    }

}