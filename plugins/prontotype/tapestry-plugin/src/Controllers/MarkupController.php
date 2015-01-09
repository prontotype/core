<?php namespace Prontotype\Plugins\Tapestry\Controllers;

use Prontotype\Http\Request;
use Prontotype\Exception\NotFoundException;
use Prontotype\Http\Controllers\BaseController;

class MarkupController extends BaseController
{    
    public function detail($path, Request $request)
    {
        $template = $this->fetchTemplate($path);
        return $this->renderTemplate('@tapestry/markup/detail.twig', [
            "path" => $path,
            "template" => $template,
            "metadata" => $template->getMetadata()
        ]);
    }

    public function preview($path, Request $request)
    {
        $template = $this->fetchTemplate($path);
        return $this->renderTemplate('@tapestry/markup/preview.twig', [
            "path" => $path,
            "template" => $template,
            "metadata" => $template->getMetadata()
        ]);
    }

    public function highlight($path, Request $request)
    {
        $template = $this->fetchTemplate($path);
        return $this->renderTemplate('@tapestry/markup/highlight.twig', [
            "path" => $path,
            "template" => $template,
            "metadata" => $template->getMetadata()
        ]);
    }

    public function raw($path, Request $request)
    {
        $template = $this->fetchTemplate($path);
        return $this->renderTemplate('@tapestry/markup/raw.twig', [
            "path" => $path,
            "template" => $template,
            "metadata" => $template->getMetadata()
        ],[
            'Content-Type' => 'text/plain'
        ]);
    }

    public function download($path, Request $request)
    {
        return $this->downloadTemplate($path);
    }

}