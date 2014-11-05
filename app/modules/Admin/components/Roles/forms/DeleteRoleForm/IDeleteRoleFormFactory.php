<?php

namespace ZaxCMS\AdminModule\Components\Roles;

interface IDeleteRoleFormFactory {

    /** @return DeleteRoleFormControl */
    public function create();

}


trait TInjectDeleteRoleFormFactory {

	protected $deleteRoleFormFactory;

	public function injectDeleteRoleFormFactory(IDeleteRoleFormFactory $deleteRoleFormFactory) {
		$this->deleteRoleFormFactory = $deleteRoleFormFactory;
	}

}

