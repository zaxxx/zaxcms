<?php

namespace Zax\Html\Icons;
use Zax,
	Nette;

interface IIcons {

	/**
	 * @param string $icon
	 * @return Nette\Utils\Html
	 */
	public function getIcon($icon);

}