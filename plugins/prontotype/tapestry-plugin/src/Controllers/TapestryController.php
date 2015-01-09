<?php namespace Prontotype\Plugins\Tapestry\Controllers;

use Prontotype\Http\Request;
use Prontotype\Exception\NotFoundException;
use Prontotype\Http\Controllers\BaseController;

class TapestryController extends BaseController
{    
    public function index(Request $request)
    {
        return $this->renderTemplate('@tapestry/index.twig');
    }

    public function markupIndex()
    {
        return $this->notFound(); // temp
    }

    public function assetsIndex()
    {
        return $this->notFound();  // temp
    }

    public function serveAsset($assetPath, Request $request)
    {

        $assetPath = make_path($this->config->get('tapestry.assetspath'), $assetPath);
        return $this->serveStatic($assetPath, $request);
    }

}