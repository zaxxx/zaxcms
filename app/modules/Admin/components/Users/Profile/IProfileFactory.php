<?php

namespace ZaxCMS\AdminModule\Components\Users;

interface IProfileFactory {

    /** @return ProfileControl */
    public function create();

}