<?php namespace Prontotype\Plugins\Tapestry\Controllers;

use Prontotype\Http\Request;
use Prontotype\Http\Controllers\BaseController;
use Prontotype\Exception\NotFoundException;

class TapestryController extends BaseController
{    
    public function index(Request $request)
    {
        $repo = $this->container->make('tapestry.repo.docs');
        try {
            $index = $repo->findIndexEntity();
        } catch( NotFoundException $e) {
            $index = null;
        }
        return $this->renderTemplate('@tapestry/index.twig', [
            "page" => $index
        ]);
    }

    public function markupIndex()
    {
        return $this->notFound(); // temp
    }

    public function resourcesIndex()
    {
        return $this->notFound();  // temp
    }

    public function serveAsset($assetPath, Request $request)
    {
        $assetPath = make_path($this->config->get('tapestry.assetspath'), $assetPath);
        return $this->serveStatic($assetPath, $request);
    }

}