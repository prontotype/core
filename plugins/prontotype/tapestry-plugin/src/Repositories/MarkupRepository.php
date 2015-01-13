<?php namespace Prontotype\Plugins\Tapestry\Repositories;

use Prontotype\Plugins\Tapestry\Filesystem\SplFileInfo;
use Prontotype\Plugins\Tapestry\Filesystem\Finder;
use Prontotype\Plugins\Tapestry\Entities\MarkupEntity;
use Prontotype\View\Twig\Template;
use Prontotype\Exception\NotFoundException;

class MarkupRepository extends AbstractRepository
{
    public function findEntity($templatePath, $allowHidden = false)
    {
        try {
            $template = $this->twig->loadTemplate(array(
                $templatePath
            ));
            if ( ! $allowHidden && $template->isHidden() ) {
                throw new NotFoundException();
            }
            return $this->wrap($template->getFile(), $this->twig);
        } catch (\Twig_Error_Loader $e) {
            throw new NotFoundException();
        }
    }

    public function getAll($path = '/', $wrap = true)
    {
        $path = make_path($this->config->get('tapestry.src.markup'), $path);
        $finder = new Finder($path);
        $result = $finder->notHidden()->isNotVariant()->hasExtensionIfFile($this->config->get('tapestry.markup.extension'));
        if ($wrap) {
            return $this->wrap($result);   
        }
        return $result;
    }

    public function getModifiersOf($tpl)
    {
        $finder = new Finder($this->config->get('tapestry.src.markup'));
        return $this->wrap($finder->notHidden()->isVariant()->modifiesEquals($tpl->getId())->hasExtensionIfFile($this->config->get('tapestry.markup.extension')));
    }

    protected function wrap($item)
    {
        if ( $item instanceof Finder) {
            $newArray = array();
            $array = iterator_to_array($item);
            foreach ($array as $iteratorItem) {
                $newArray[] = $this->wrap($iteratorItem);
            }
            return new \ArrayIterator($newArray);
        } else {
            return new MarkupEntity($item, $this->twig);
        }
        return $item;
    }

}