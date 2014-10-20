<?php

namespace ZaxCMS\AdminModule\Components\Mails;

interface ITestMailFormFactory {

    /** @return TestMailFormControl */
    public function create();

}