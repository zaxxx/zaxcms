<?php

namespace ZaxCMS\Components\Menu;

interface IEditMenuItemFactory {

	/** @return EditMenuItemControl */
	public function create();

}