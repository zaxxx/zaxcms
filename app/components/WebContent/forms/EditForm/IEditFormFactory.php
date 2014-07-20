<?php

namespace ZaxCMS\Components\WebContent;

interface IEditFormFactory {

    /** @return EditFormControl */
    public function create();

}