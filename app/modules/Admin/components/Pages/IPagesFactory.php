<?php

namespace ZaxCMS\AdminModule\Components\Pages;

interface IPagesFactory {

    /** @return PagesControl */
    public function create();

}