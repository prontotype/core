<?php namespace Prontotype\Http\Controllers;

use Prontotype\Http\Request;

class DefaultController extends BaseController
{    
    public function catchall($templatePath, Request $request)
    {
        return $this->renderTemplate($templatePath, [], [
            'Content-Type' => $request->getRequestMime()
        ]);
    }
}