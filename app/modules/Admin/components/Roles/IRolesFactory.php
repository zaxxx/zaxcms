<?php

namespace ZaxCMS\AdminModule\Components\Roles;

interface IRolesFactory {

    /** @return RolesControl */
    public function create();

}


trait TInjectRolesFactory {

	/** @var  IRolesFactory */
	protected $rolesFactory;

	public function injectRolesFactory(IRolesFactory $rolesFactory) {
		$this->rolesFactory = $rolesFactory;
	}

}

