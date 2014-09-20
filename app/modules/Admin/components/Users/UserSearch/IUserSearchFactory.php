<?php

namespace ZaxCMS\AdminModule\Components\Users;

interface IUserSearchFactory {

    /** @return UserSearchControl */
    public function create();

}