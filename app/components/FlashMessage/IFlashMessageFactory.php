<?php

namespace ZaxCMS\Components\FlashMessage;

interface IFlashMessageFactory {

	/** @return FlashMessageControl */
	public function create();

}