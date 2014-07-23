<?php

namespace ZaxCMS\InstallModule\Components;

interface ICreateUserFactory {

    /** @return CreateUserControl */
    public function create();

}