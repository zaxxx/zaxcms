<?php

namespace ZaxCMS\Components\Menu;

interface IDeleteMenuItemFormFactory {

    /** @return DeleteMenuItemFormControl */
    public function create();

}