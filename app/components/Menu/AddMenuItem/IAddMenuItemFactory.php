<?php

namespace ZaxCMS\Components\Menu;

interface IAddMenuItemFactory {

    /** @return AddMenuItemControl */
    public function create();

}