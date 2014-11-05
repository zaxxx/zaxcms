<?php

namespace ZaxCMS\AdminModule\Components\Roles;

interface IAddRoleFormFactory {

    /** @return AddRoleFormControl */
    public function create();

}


trait TInjectAddRoleFormFactory {

	protected $addRoleFormFactory;

	public function injectAddRoleFormFactory(IAddRoleFormFactory $addRoleFormFactory) {
		$this->addRoleFormFactory = $addRoleFormFactory;
	}

}

