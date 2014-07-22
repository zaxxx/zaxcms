<?php

namespace ZaxCMS\Components\Menu;

interface IEditMenuItemFormFactory {

    /** @return EditMenuItemFormControl */
    public function create();

}