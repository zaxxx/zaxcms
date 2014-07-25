<?php

namespace ZaxCMS\Components\Auth;

interface ILoginFormFactory {

    /** @return LoginFormControl */
    public function create();

}