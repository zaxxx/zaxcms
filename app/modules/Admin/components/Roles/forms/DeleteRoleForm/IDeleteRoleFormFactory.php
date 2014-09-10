<?php

namespace ZaxCMS\AdminModule\Components\Roles;

interface IDeleteRoleFormFactory {

    /** @return DeleteRoleFormControl */
    public function create();

}