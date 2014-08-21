<?php

namespace ZaxCMS\Components\Navigation;

interface IEditFactory {

	/** @return EditControl */
	public function create();

}