<?php

namespace ZaxCMS\AdminModule\Components\Roles;

interface IPermissionsFactory {

    /** @return PermissionsControl */
    public function create();

}