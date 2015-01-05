<?php namespace Prontotype\Http\Controllers;

use Prontotype\Http\Request;
use Prontotype\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends BaseController
{    
    public function catchall($templatePath, Request $request)
    {
        return $this->renderTemplate($templatePath, [], [
            'Content-Type' => $request->getRequestMime()
        ]);
    }

    public function redirectById($templateId, Request $request)
    {
        $template = $this->fetchTemplate('id:' . $templateId, true);
        $path = preg_replace('/' . '\.' . $this->config->get('templates.extension') . '$/', '', $template->getRelativePathname());
        return new RedirectResponse($path, 301);
    }

}