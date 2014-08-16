<?php

namespace ZaxCMS\AdminModule\Components\Pages;

interface IEditPageFormFactory {

    /** @return EditPageFormControl */
    public function create();

}