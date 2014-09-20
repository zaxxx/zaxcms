<?php

namespace ZaxCMS\Components\Navigation\MarkActiveStrategies;
use Zax,
	ZaxCMS,
	Nette;

class PresenterStrategy extends Strategy {

	public function isActive(ZaxCMS\Model\CMS\Entity\Menu $menuItem) {
		$split = explode(':', $menuItem->nhref, -1);
		array_shift($split);
		return $this->request->presenterName === implode(':', $split);
	}

}