<?php

namespace Zax\Forms\Controls;
use Nette,
	Nette\Forms\Controls\Button;

/**
 * Class LinkSubmitButton
 *
 * @package Zax\Forms\Controls
 */
class LinkSubmitButton extends Button {

	public function __construct($caption = NULL) {
		parent::__construct($caption);
		$this->control->setName('a');
	}

}
