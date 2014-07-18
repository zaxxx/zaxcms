<?php

namespace ZaxCMS\Components\Menu;

interface IMenuWrapperFactory {

    /** @return MenuWrapperControl */
    public function create();

}