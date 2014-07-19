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
     * Basically a opy&paste from Nette DefaultFormRenderer - credits to David Grudl
     * 
     * I only changed "control" to "submits" right before return keyword.
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
