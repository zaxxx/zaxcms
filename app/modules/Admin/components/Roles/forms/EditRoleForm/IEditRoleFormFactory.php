<?php

namespace ZaxCMS\AdminModule\Components\Roles;

interface IEditRoleFormFactory {

    /** @return EditRoleFormControl */
    public function create();

}


trait TInjectEditRoleFormFactory {

	protected $editRoleFormFactory;

	public function injectEditRoleFormFactory(IEditRoleFormFactory $editRoleFormFactory) {
		$this->editRoleFormFactory = $editRoleFormFactory;
	}

}

