<?php

namespace ZaxCMS\Components\Articles;

interface IArticlesFactory {

    /** @return ArticlesControl */
    public function create();

}