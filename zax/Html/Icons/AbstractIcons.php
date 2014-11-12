<?php

namespace Zax\Html\Icons;
use Zax,
	Nette;

abstract class AbstractIcons extends Nette\Object implements IIcons {

	protected $prefix = '';

	protected $availableIcons = [];

	public function getIcon($icon) {
		if(array_key_exists($icon, $this->availableIcons)) {
			$icon = $this->availableIcons[$icon];
		}
		return Nette\Utils\Html::el('span')
			->class($this->prefix . $icon);
	}

	public function getAvailableIcons() {
		return array_values($this->availableIcons);
	}

}