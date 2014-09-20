<?php

namespace ZaxCMS\AdminModule\Components\Users;

interface IUserSortFactory {

    /** @return UserSortControl */
    public function create();

}