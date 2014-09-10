<?php

namespace ZaxCMS\AdminModule\Components\Roles;

interface IRolesFactory {

    /** @return RolesControl */
    public function create();

}