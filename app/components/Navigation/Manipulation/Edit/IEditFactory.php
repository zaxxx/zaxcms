<?php

namespace ZaxCMS\Components\Navigation;

interface IEditFactory {

	/** @return EditControl */
	public function create();

}



trait TInjectEditFactory {

	/** @var IEditFactory */
	protected $editFactory;

	public function injectEditFactory(IEditFactory $editFactory) {
		$this->editFactory = $editFactory;
	}

}


