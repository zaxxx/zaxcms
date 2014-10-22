<?php

namespace ZaxCMS\Components\Article;

interface IArticleListFactory {

    /** @return ArticleListControl */
    public function create();

}