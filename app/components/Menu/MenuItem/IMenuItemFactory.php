<?php

namespace ZaxCMS\Components\Menu;

interface IMenuItemFactory {

	/** @return MenuItemControl */
	public function create();

}