<?php

namespace Zax\Forms\Controls;
use Nette,
    Nette\Forms\Helpers,
    Nette\Forms\Form,
    Nette\Utils\Html,
    Nette\Forms\Controls\BaseControl;

/**
 * Class StaticControl
 *
 * @package Zax\Forms\Controls
 */
class StaticControl extends BaseControl {

    protected $value;

	public function loadHttpData() {

	}
    
    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

    public function getValue() {
        return $this->value;
    }

    public function getControl() {
        $this->setOption('rendered', TRUE);

		// '.form-group' ensures proper vertical alignment in inline forms
        return Html::el('p')->id($this->getHtmlId())->class('form-control-static form-group')->setText($this->value);
    }
}
 