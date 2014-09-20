<?php

namespace ZaxCMS\Components\Navigation\MarkActiveStrategies;
use Zax,
	ZaxCMS,
	Nette;

class PageStrategy extends Strategy {

	public function isValidForItem(ZaxCMS\Model\CMS\Entity\Menu $menuItem) {
		return $menuItem->getPresenterName() === 'Front:Page' && isset($this->request->parameters['page']);
	}

	public function isActive(ZaxCMS\Model\CMS\Entity\Menu $menuItem) {
		$activePresenter = $this->request->presenterName === $menuItem->getPresenterName();
		$activePage = isset($this->request->parameters['page']) && $this->request->parameters['page'] === $menuItem->nhrefParams['page'];
		return $activePresenter && $activePage;
	}

}