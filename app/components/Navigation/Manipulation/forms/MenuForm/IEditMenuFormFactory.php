<?php

namespace ZaxCMS\Components\Navigation;

interface IEditMenuFormFactory {

    /** @return EditMenuFormControl */
    public function create();

}