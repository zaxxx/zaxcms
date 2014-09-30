<?php

namespace ZaxCMS\AdminModule\Components\Users;

interface IAddUserFormFactory {

    /** @return AddUserFormControl */
    public function create();

}