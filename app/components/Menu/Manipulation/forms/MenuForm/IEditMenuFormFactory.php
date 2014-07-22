<?php

namespace ZaxCMS\Components\Menu;

interface IEditMenuFormFactory {

    /** @return EditMenuFormControl */
    public function create();

}