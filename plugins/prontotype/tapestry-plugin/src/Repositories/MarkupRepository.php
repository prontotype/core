<?php namespace Prontotype\Plugins\Tapestry\Repositories;

use Prontotype\Plugins\Tapestry\Filesystem\SplFileInfo;
use Prontotype\Plugins\Tapestry\Filesystem\Finder;
use Prontotype\View\Twig\Template;

class MarkupRepository extends AbstractRepository {

    public function getAll($path = '/')
    {
        $path = make_path($this->config->get('templates.directory'), $path);
        $finder = new Finder($path);
        return $finder->notHidden()->isNotVariant()->hasExtensionIfFile($this->config->get('templates.extension'));
    }

    public function getVariantsOf(Template $tpl)
    {
        $finder = new Finder($this->config->get('templates.directory'));
        return $finder->notHidden()->isVariant()->extendsEquals($tpl->getId())->hasExtensionIfFile($this->config->get('templates.extension'));
    }
}