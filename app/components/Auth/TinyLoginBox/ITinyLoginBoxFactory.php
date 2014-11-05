<?php

namespace ZaxCMS\Components\Auth;

interface ITinyLoginBoxFactory {

    /** @return TinyLoginBoxControl */
    public function create();

}


trait TInjectTinyLoginBoxFactory {

	protected $tinyLoginBoxFactory;

	public function injectTinyLoginBoxFactory(ITinyLoginBoxFactory $tinyLoginBoxFactory) {
		$this->tinyLoginBoxFactory = $tinyLoginBoxFactory;
	}

}

