<?php

namespace ZaxCMS\Components\StaticLinker;

interface ICssLinkerFactory extends ILinkerFactory {

	/** @return CssLinkerControl */
	public function create();

}