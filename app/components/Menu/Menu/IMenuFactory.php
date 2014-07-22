<?php

namespace ZaxCMS\Components\Menu;

interface IMenuFactory {

	/** @return MenuControl */
	public function create();

}
