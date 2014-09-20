<?php

namespace ZaxCMS\AdminModule\Components\Users;

interface IUsersFactory {

    /** @return UsersControl */
    public function create();

}