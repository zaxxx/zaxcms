<?php

namespace ZaxCMS\AdminModule\Components\Users;

interface IEditUserFormFactory {

    /** @return EditUserFormControl */
    public function create();

}