<?php

namespace ZaxCMS\AdminModule\Components\Roles;

interface IEditRoleFormFactory {

    /** @return EditRoleFormControl */
    public function create();

}