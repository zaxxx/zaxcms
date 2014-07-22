<?php

namespace ZaxCMS\Components\StaticLinker;

interface IStaticLinkerFactory {

	/** @return StaticLinkerControl */
	public function create();

}