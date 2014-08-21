<?php

namespace ZaxCMS\Components\Navigation;

interface IEditMenuItemFactory {

	/** @return EditMenuItemControl */
	public function create();

}