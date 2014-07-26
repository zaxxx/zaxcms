<?php

namespace ZaxCMS\InstallModule\Components\Install;

interface ICreateUserFactory {

    /** @return CreateUserControl */
    public function create();

}