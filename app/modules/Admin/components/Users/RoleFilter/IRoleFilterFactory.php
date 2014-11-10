<?php

namespace ZaxCMS\AdminModule\Components\Users;

interface IRoleFilterFactory {

    /** @return RoleFilterControl */
    public function create();

}


trait TInjectRoleFilterFactory {

	protected $roleFilterFactory;

	public function injectRoleFilterFactory(IRoleFilterFactory $roleFilterFactory) {
		$this->roleFilterFactory = $roleFilterFactory;
	}

}

