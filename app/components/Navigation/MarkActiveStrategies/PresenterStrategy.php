<?php

namespace ZaxCMS\Components\Navigation\MarkActiveStrategies;
use Zax,
	ZaxCMS,
	Nette;

class PresenterStrategy extends Strategy {

	public function isValidForItem(ZaxCMS\Model\CMS\Entity\Menu $menuItem) {
		return $menuItem->nhref !== NULL;
	}

	public function isActive(ZaxCMS\Model\CMS\Entity\Menu $menuItem) {
		return $this->request->presenterName === $menuItem->getPresenterName();
	}

}