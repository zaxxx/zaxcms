<?php

namespace Zax\Components\Menu;

interface IMenuItemFactory {

    /** @return MenuItemControl */
    public function create();

}