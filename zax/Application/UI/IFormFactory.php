<?php

namespace Zax\Application\UI;
use Zax,
	Nette;

interface IFormFactory {

	/**
	 * @return Nette\Forms\Form
	 */
	public function create();

}