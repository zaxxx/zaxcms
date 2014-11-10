<?php

namespace ZaxCMS\Components\WebContent;

interface IEditFactory {

	/** @return EditControl */
	public function create();

}


trait TInjectEditFactory {

	protected $editFactory;

	public function injectEditFactory(IEditFactory $editFactory) {
		$this->editFactory = $editFactory;
	}

}

