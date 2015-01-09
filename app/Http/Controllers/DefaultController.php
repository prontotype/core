<?php namespace Prontotype\Http\Controllers;

use Prontotype\Http\Request;
use Prontotype\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DefaultController extends BaseController
{    
    public function catchall($templatePath, Request $request)
    {
        return $this->renderTemplate($templatePath, [], [
            'Content-Type' => $request->getRequestMime()
        ]);
    }
}