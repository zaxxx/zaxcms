<?php

namespace ZaxCMS\AdminModule\Components\Users;

interface IBasicInfoFactory {

    /** @return BasicInfoControl */
    public function create();

}