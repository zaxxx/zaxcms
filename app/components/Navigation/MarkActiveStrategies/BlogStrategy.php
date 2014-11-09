<?php

namespace ZaxCMS\Components\Navigation\MarkActiveStrategies;
use Zax,
	ZaxCMS,
	Nette;

class BlogStrategy extends Strategy {

	public function isValidForItem(ZaxCMS\Model\CMS\Entity\Menu $menuItem) {
		$presenter = $menuItem->getPresenterName();
		return strrpos($presenter, ':Blog') === strlen($presenter) - 5;
	}

	public function isActive(ZaxCMS\Model\CMS\Entity\Menu $menuItem) {
		$activePresenter = $this->request->presenterName === $menuItem->getPresenterName();
		$actionMatch = $this->request->parameters['action'] === $menuItem->getActionName();
		if(!$activePresenter || !$actionMatch) {
			return FALSE;
		}
		$slugs = [
			'category',
			'article',
			'author',
			'tag'
		];
		$isAnySet = FALSE;
		foreach($slugs as $slug) {
			if(isset($menuItem->nhrefParams[$slug . '-slug'])) {
				$isAnySet = TRUE;
				if(isset($this->request->parameters[$slug . '-slug']) && $this->request->parameters[$slug . '-slug'] === $menuItem->nhrefParams[$slug . '-slug']) {
					return TRUE;
				}
			}
		}
		return !$isAnySet;
	}

}