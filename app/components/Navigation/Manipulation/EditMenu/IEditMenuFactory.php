<?php

namespace ZaxCMS\Components\Navigation;

interface IEditMenuFactory {

	/** @return EditMenuControl */
	public function create();

}