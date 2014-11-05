<?php

namespace ZaxCMS\AdminModule\Components\Roles;

interface IPermissionsFactory {

    /** @return PermissionsControl */
    public function create();

}


trait TInjectPermissionsFactory {

	/** @var IPermissionsFactory */
	protected $permissionsFactory;

	public function injectPermissionFactory(IPermissionsFactory $permissionsFactory) {
		$this->permissionsFactory = $permissionsFactory;
	}

}

