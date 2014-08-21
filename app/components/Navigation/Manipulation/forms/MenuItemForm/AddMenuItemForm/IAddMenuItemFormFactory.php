<?php

namespace ZaxCMS\Components\Navigation;

interface IAddMenuItemFormFactory {

    /** @return AddMenuItemFormControl */
    public function create();

}