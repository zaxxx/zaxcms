<?php

namespace ZaxCMS\Components\WebContent;

interface IEditFactory {

    /** @return EditControl */
    public function create();

}