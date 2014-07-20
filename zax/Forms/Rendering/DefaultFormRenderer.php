<?php

namespace Zax\Forms\Rendering;
use Nette,
	Nette\Utils\Html,
    Zax;

/**
 * Class DefaultFormRenderer
 *
 * Support for $wrappers[submits][container]
 *
 * @package Zax\Forms\Rendering
 */
class DefaultFormRenderer extends Nette\Forms\Rendering\DefaultFormRenderer {
    
    public function __construct() {
        $this->wrappers['submits']['container'] = 'div';
    }

	public function init() {

	}
    
    /*
     * The following method is derived from code of Nette Framework 2.2
     *
     * Code subject to the new BSD license (http://nette.org/cs/license#toc-new-bsd-license).
     *
     * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
     */
    public function renderPairMulti(array $controls) {
        $s = array();
        foreach($controls as $control) {
            if(!$control instanceof Nette\Forms\IControl) {
                throw new Nette\InvalidArgumentException('Argument must be array of IFormControl instances.');
            }
            $description = $control->getOption('description');
            if($description instanceof Html) {
                $description = ' ' . $control->getOption('description');
            } else if(is_string($description)) {
                $description = ' ' . $this->getWrapper('control description')->setText($control->translate($description));
            } else {
                $description = '';
            }

            $s[] = $control->getControl() . $description;
        }
        $pair = $this->getWrapper('pair container');
        $pair->add($this->renderLabel($control));
        $pair->add($this->getWrapper('submits container')->setHtml(implode(' ', $s)));
        
        return $pair->render(0);
    }
}
