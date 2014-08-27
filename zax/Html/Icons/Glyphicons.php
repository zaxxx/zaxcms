<?php

namespace Zax\Html\Icons;
use Zax,
	Nette;

class Glyphicons extends Nette\Object implements IIcons {

	protected $prefix = 'glyphicon glyphicon-';

	public function getIcon($icon) {
		return Nette\Utils\Html::el('span')
			->class($this->prefix . $icon);
	}

}