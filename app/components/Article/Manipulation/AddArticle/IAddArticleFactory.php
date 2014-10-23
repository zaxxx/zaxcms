<?php

namespace ZaxCMS\Components\Article;

interface IAddArticleFactory {

    /** @return AddArticleControl */
    public function create();

}