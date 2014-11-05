<?php

namespace ZaxCMS\AdminModule\Components\Pages;

interface IEditPageFormFactory {

    /** @return EditPageFormControl */
    public function create();

}


trait TInjectEditPageFormFactory {

	protected $editPageFormFactory;

	public function injectEditPageFormFactory(IEditPageFormFactory $editPageFormFactory) {
		$this->editPageFormFactory = $editPageFormFactory;
	}

}

