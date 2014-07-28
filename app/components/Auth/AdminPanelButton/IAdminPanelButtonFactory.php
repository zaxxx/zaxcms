<?php

namespace ZaxCMS\Components\Auth;

interface IAdminPanelButtonFactory {

    /** @return AdminPanelButtonControl */
    public function create();

}