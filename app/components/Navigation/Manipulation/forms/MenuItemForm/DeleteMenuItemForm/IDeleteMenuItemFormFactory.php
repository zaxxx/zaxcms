<?php

namespace ZaxCMS\Components\Navigation;

interface IDeleteMenuItemFormFactory {

    /** @return DeleteMenuItemFormControl */
    public function create();

}