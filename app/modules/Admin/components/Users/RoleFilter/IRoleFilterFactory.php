<?php

namespace ZaxCMS\AdminModule\Components\Users;

interface IRoleFilterFactory {

    /** @return RoleFilterControl */
    public function create();

}