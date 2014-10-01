<?php

namespace ZaxCMS\Components\Auth;

interface IChangePasswordFactory {

    /** @return ChangePasswordControl */
    public function create();

}