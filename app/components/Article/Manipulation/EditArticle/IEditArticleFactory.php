<?php

namespace ZaxCMS\Components\Article;

interface IEditArticleFactory {

    /** @return EditArticleControl */
    public function create();

}