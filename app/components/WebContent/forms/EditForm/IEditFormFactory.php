<?php

namespace ZaxCMS\Components\WebContent;

interface IEditFormFactory {

	/** @return EditFormControl */
	public function create();

}


trait TInjectEditFormFactory {

	protected $editFormFactory;

	public function injectEditFormFactory(IEditFormFactory $editFormFactory) {
		$this->editFormFactory = $editFormFactory;
	}

}

