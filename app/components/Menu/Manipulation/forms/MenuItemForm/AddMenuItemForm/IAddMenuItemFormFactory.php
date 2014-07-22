<?php

namespace ZaxCMS\Components\Menu;

interface IAddMenuItemFormFactory {

    /** @return AddMenuItemFormControl */
    public function create();

}