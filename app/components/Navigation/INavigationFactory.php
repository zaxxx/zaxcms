<?php

namespace ZaxCMS\Components\Navigation;

interface INavigationFactory {

    /** @return NavigationControl */
    public function create();

}