<?php

namespace ZaxCMS\Components\Category;

interface ICategoryFactory {

    /** @return CategoryControl */
    public function create();

}