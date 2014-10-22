<?php

namespace ZaxCMS\Components\Article;

interface IArticleFactory {

    /** @return ArticleControl */
    public function create();

}