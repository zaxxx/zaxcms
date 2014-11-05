<?php

namespace ZaxCMS\Components\WebContent;

interface IWebContentFactory {

    /** @return WebContentControl */
	public function create();

}


trait TInjectWebContentFactory {

	protected $webContentFactory;

	public function injectWebContentFactory(IWebContentFactory $webContentFactory) {
		$this->webContentFactory = $webContentFactory;
	}

}

