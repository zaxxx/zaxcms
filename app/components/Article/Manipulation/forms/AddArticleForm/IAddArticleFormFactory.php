<?php

namespace ZaxCMS\Components\Article;

interface IAddArticleFormFactory {

    /** @return AddArticleFormControl */
    public function create();

}