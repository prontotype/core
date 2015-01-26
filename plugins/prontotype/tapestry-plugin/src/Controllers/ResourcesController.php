<?php namespace Prontotype\Plugins\Tapestry\Controllers;

use Prontotype\Http\Request;
use Prontotype\Exception\NotFoundException;
use Prontotype\Http\Controllers\BaseController;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ResourcesController extends BaseController
{   
    public function index()
    {
        return $this->renderTemplate('@tapestry/resources/index.twig');
    }

    public function detail($group, $path, Request $request)
    {
        return $this->getResponse($group, $path, 'detail');
    }

    public function preview($group, $path, Request $request)
    {
        $repo = $this->container->make('tapestry.repo.resources');
        $group = $repo->getGroup($group);
        $entity = $repo->findEntity($group, $path);
        $response = $this->renderTemplate('@tapestry/resources/preview.twig', [
            "resource" => $entity,
            "group" => $group
        ]);
        return $response;
    }

    public function raw($group, $path, Request $request)
    {
        $repo = $this->container->make('tapestry.repo.resources');
        $group = $repo->getGroup($group);
        $entity = $repo->findEntity($group, $path);
        $response = $this->renderTemplate('@tapestry/resources/raw.twig', [
            "resource" => $entity,
        ],[
            'Content-Type' => $entity->getMimeType()
        ]);
        return $response;
    }

    public function download($group, $path, Request $request)
    {
        $repo = $this->container->make('tapestry.repo.resources');
        $group = $repo->getGroup($group);
        $entity = $repo->findEntity($group, $path);
        $response = $this->renderTemplate('@tapestry/resources/download.twig', [
            "resource" => $entity,
        ]);
        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $entity->getFilename()
        ));
        return $response;
    }

    protected function getResponse($groupKey, $path, $tpl, $attr = array())
    {
        $repo = $this->container->make('tapestry.repo.resources');
        $group = $repo->getGroup($groupKey);
        $entity = $repo->findEntity($group, $path);
        return $this->renderTemplate('@tapestry/resources/' . $tpl . '.twig', [
            "resource" => $entity,
            "group" => $group
        ], $attr);
    }


}