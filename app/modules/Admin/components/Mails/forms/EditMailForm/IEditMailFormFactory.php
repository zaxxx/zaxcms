<?php

namespace ZaxCMS\AdminModule\Components\Mails;

interface IEditMailFormFactory {

    /** @return EditMailFormControl */
    public function create();

}