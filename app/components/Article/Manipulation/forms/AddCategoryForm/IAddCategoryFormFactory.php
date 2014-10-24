<?php

namespace ZaxCMS\Components\Article;

interface IAddCategoryFormFactory {

    /** @return AddCategoryFormControl */
    public function create();

}