<?php

namespace ZaxCMS\Components\Menu;

interface IEditFactory {

    /** @return EditControl */
    public function create();

}