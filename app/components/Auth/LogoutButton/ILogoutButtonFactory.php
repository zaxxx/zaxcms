<?php

namespace ZaxCMS\Components\Auth;

interface ILogoutButtonFactory {

    /** @return LogoutButtonControl */
    public function create();

}