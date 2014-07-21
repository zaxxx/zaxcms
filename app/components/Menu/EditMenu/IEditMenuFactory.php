<?php

namespace ZaxCMS\Components\Menu;

interface IEditMenuFactory {

	/** @return EditMenuControl */
	public function create();

}