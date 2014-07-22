<?php

namespace ZaxCMS\Components\StaticLinker;

interface IJsLinkerFactory extends ILinkerFactory {

	/** @return JsLinkerControl */
	public function create();

}