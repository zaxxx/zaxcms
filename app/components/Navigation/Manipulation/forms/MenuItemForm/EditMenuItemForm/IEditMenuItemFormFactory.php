<?php

namespace ZaxCMS\Components\Navigation;

interface IEditMenuItemFormFactory {

    /** @return EditMenuItemFormControl */
    public function create();

}