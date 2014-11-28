<?php namespace Prontotype\Http\Controllers;

use Amu\SuperSharp\Http\Response;
use Amu\SuperSharp\Http\Request;

class DefaultController extends BaseController
{    
    public function catchall($urlPath, Request $request)
    {
        $template = $this->findViewTemplateByUrl($urlPath);
        return new Response($template->render(), 200, [
            'Content-Type' => $request->getRequestMime()
        ]);
    }
}