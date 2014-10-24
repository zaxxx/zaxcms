<?php

namespace ZaxCMS\Components\Navigation\MarkActiveStrategies;
use Zax,
	ZaxCMS,
	Nette;

class CategoryStrategy extends Strategy {

	public function isValidForItem(ZaxCMS\Model\CMS\Entity\Menu $menuItem) {
		$presenter = $menuItem->getPresenterName();
		return strrpos($presenter, ':Category') === strlen($presenter) - 9 && isset($this->request->parameters['slug']);
	}

	public function isActive(ZaxCMS\Model\CMS\Entity\Menu $menuItem) {
		$activePresenter = $this->request->presenterName === $menuItem->getPresenterName();
		$activeCategory = isset($this->request->parameters['slug']) && $this->request->parameters['slug'] === $menuItem->nhrefParams['slug'];
		return $activePresenter && $activeCategory;
	}

}