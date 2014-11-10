<?php

namespace ZaxCMS\AdminModule\Components\Users;

interface IAddUserFormFactory {

    /** @return AddUserFormControl */
    public function create();

}


trait TInjectAddUserFormFactory {

	protected $addUserFormFactory;

	public function injectAddUserFormFactory(IAddUserFormFactory $addUserFormFactory) {
		$this->addUserFormFactory = $addUserFormFactory;
	}

}

