<?php

namespace ZaxCMS\Components\Navigation\MarkActiveStrategies;
use Zax,
	ZaxCMS,
	Nette;

class CategoryStrategy extends Strategy {

	public function isValidForItem(ZaxCMS\Model\CMS\Entity\Menu $menuItem) {
		$presenter = $menuItem->getPresenterName();
		return strrpos($presenter, ':Category') === strlen($presenter) - 9 && isset($this->request->parameters['category-slug']);
	}

	public function isActive(ZaxCMS\Model\CMS\Entity\Menu $menuItem) {
		$activePresenter = $this->request->presenterName === $menuItem->getPresenterName();
		$activeCategory = isset($this->request->parameters['category-slug']) && $this->request->parameters['category-slug'] === $menuItem->nhrefParams['category-slug'];
		return $activePresenter && $activeCategory;
	}

}