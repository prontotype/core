<?php namespace Prontotype\Http\Controllers;

use Prontotype\Http\Request;
use Prontotype\Exception\NotFoundException;

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

}