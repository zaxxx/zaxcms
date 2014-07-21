<?php

namespace ZaxCMS\Components\WebContent;

interface IWebContentFactory {

    /** @return WebContentControl */
	public function create();

}