<?php

namespace ZaxCMS\Components\Article;

interface IEditArticleFormFactory {

    /** @return EditArticleFormControl */
    public function create();

}