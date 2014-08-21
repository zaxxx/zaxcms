<?php

namespace ZaxCMS\Components\Navigation;

interface IAddMenuItemFactory {

    /** @return AddMenuItemControl */
	public function create();

}