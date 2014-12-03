<?php namespace Prontotype\View\Twig\Prontotype;

use Amu\Dayglo\Loader as DataLoader;

class ProntotypeExtension extends \Twig_Extension
{
    public function __construct(DataLoader $loader)
    {
        $this->dataloader = $loader;
    }

    public function getGlobals()
    {
        return array(
            'pt' => array(
                'data' => $this->dataloader
            )
        );
    }

    public function getName()
    {
        return 'prontotype';
    }

}