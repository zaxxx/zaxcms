<?php

namespace Zax\Html\Icons;
use Zax,
	Nette;

abstract class AbstractIcons extends Nette\Object implements IIcons {

	protected $prefix = '';

	protected $availableIcons = [];

	protected $cachedIcons = [];

	public function getIcon($icon) {
		if(!isset($this->cachedIcons[$icon])) {
			if (array_key_exists($icon, $this->availableIcons)) {
				$icon = $this->availableIcons[$icon];
			}
			$this->cachedIcons[$icon] = Nette\Utils\Html::el('span')
				->class($this->prefix . $icon);
		}
		return $this->cachedIcons[$icon];
	}

	public function getAvailableIcons() {
		return array_values($this->availableIcons);
	}

}