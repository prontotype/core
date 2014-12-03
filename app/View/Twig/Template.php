<?php namespace Prontotype\View\Twig;

use Twig_Template, Twig_TemplateInterface;
use Dflydev\ApacheMimeTypes\PhpRepository as MimeRepo;

abstract class Template extends Twig_Template
{

    public function display(array $context, array $blocks = array())
    {
        parent::display($context, $blocks);
    }

    public function getMimeType()
    {
        $ext = strtolower(pathinfo($this->getTemplateName(), PATHINFO_EXTENSION));
        $mimes = new MimeRepo();
        return $mimes->findType($ext);
    }

    public function render(array $context = array())
    {
        return parent::render($context);
    }

}