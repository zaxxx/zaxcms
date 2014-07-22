<?php

namespace ZaxCMS\Components\StaticLinker;

interface ILinkerFactory {

	/** @return ILinker */
	public function create();

}