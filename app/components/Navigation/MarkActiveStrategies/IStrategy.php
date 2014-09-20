<?php

namespace ZaxCMS\Components\Navigation\MarkActiveStrategies;
use Zax,
	ZaxCMS,
	Nette;

interface IStrategy {

	/**
	 * Check whether it's possible to determine whether this menu item can be determined as active
	 * (eg. presenter name matches and has a specific parameter specified) by selected strategy
	 *
	 * @param ZaxCMS\Model\CMS\Entity\Menu $menuItem
	 * @return bool
	 */
	public function isValidForItem(ZaxCMS\Model\CMS\Entity\Menu $menuItem);

	/**
	 * See if this item really is active for current action
	 *
	 * @param ZaxCMS\Model\CMS\Entity\Menu $menuItem
	 * @return bool
	 */
	public function isActive(ZaxCMS\Model\CMS\Entity\Menu $menuItem);

}