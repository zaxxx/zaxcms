<?php

namespace ZaxCMS\AdminModule\Components\Roles;

interface IAddRoleFormFactory {

    /** @return AddRoleFormControl */
    public function create();

}