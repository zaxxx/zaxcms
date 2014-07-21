<?php

namespace Zax\Components\StaticLinker;

interface ILinkerFactory {

	/** @return ILinker */
	public function create();

}