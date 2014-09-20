<?php

namespace ZaxCMS\Components\Navigation\MarkActiveStrategies;
use Zax,
	ZaxCMS,
	Nette;

class StrategyList extends Nette\Object implements IStrategy {

	protected $strategies = [];

	public function addStrategy(IStrategy $strategy) {
		$this->strategies[] = $strategy;
		return $this;
	}

	public function isValidForItem(ZaxCMS\Model\CMS\Entity\Menu $menuItem) {
		return TRUE;
	}

	public function isActive(ZaxCMS\Model\CMS\Entity\Menu $menuItem) {
		foreach($this->strategies as $strategy) {
			if($strategy->isValidForItem($menuItem)) {
				return $strategy->isActive($menuItem);
			}
		}
		return FALSE;
	}

}