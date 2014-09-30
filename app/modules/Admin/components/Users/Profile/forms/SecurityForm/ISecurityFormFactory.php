<?php

namespace ZaxCMS\AdminModule\Components\Users;

interface ISecurityFormFactory {

    /** @return SecurityFormControl */
    public function create();

}