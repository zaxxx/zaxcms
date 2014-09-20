<?php

namespace ZaxCMS\AdminModule\Components\Users;

interface ISecurityInfoFactory {

    /** @return SecurityInfoControl */
    public function create();

}