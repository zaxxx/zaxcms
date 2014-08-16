<?php

namespace ZaxCMS\AdminModule\Components\Pages;

interface IDeletePageFormFactory {

    /** @return DeletePageFormControl */
    public function create();

}