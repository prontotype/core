<?php namespace Prontotype\View\Twig\Extension;

use Prontotype\View\Globals;

class ProntotypeExtension extends \Twig_Extension
{
    public function __construct(Globals $globals)
    {
        $this->globals = $globals;
    }

    public function getGlobals()
    {
        return array(
            'pt' => $this->globals->all()
        );
    }

    public function getName()
    {
        return 'prontotype';
    }

}

