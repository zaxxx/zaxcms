<?php

namespace ZaxCMS\Components\FlashMessage;

interface IFlashMessageFactory {

	/** @return FlashMessageControl */
	public function create();

}


trait TInjectFlashMessageFactory {

	protected $flashMessageFactory;

	public function injectFlashMessageFactory(IFlashMessageFactory $flashMessageFactory) {
		$this->flashMessageFactory = $flashMessageFactory;
	}

}

