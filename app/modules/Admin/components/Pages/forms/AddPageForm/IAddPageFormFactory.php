<?php

namespace ZaxCMS\AdminModule\Components\Pages;

interface IAddPageFormFactory {

    /** @return AddPageFormControl */
    public function create();

}