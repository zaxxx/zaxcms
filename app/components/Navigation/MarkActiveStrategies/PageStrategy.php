<?php

namespace ZaxCMS\Components\Navigation\MarkActiveStrategies;
use Zax,
	ZaxCMS,
	Nette;

class PageStrategy extends Strategy {

	public function isActive(ZaxCMS\Model\CMS\Entity\Menu $menuItem) {
		$split = explode(':', $menuItem->nhref, -1);
		array_shift($split);
		$activePresenter = $this->request->presenterName === implode(':', $split);
		$activePage = isset($this->request->parameters['page']) && $this->request->parameters['page'] === $menuItem->nhrefParams['page'];
		return $activePresenter && $activePage;
	}

}