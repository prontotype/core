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
        if ($this->config->get('short_urls')) {
            $template = $this->fetchTemplate('id:' . $templateId, true);
            $path = preg_replace('/' . '\.' . $this->config->get('templates.extension') . '$/', '', $template->getRelativePathname());
            return new RedirectResponse($path, 301);
        } else {
            throw new NotFoundException('Page not found');
        }
    }
}