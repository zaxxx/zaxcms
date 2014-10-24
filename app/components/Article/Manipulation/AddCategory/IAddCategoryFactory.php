<?php

namespace ZaxCMS\Components\Article;

interface IAddCategoryFactory {

    /** @return AddCategoryControl */
    public function create();

}