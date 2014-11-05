<?php

namespace ZaxCMS\AdminModule\Components\Users;

interface IEditUserFormFactory {

    /** @return EditUserFormControl */
    public function create();

}


trait TInjectEditUserFormFactory {

	protected $editUserFormFactory;

	public function injectEditUserFormFactory(IEditUserFormFactory $editUserFormFactory) {
		$this->editUserFormFactory = $editUserFormFactory;
	}

}

