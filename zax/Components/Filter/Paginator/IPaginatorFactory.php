<?php

namespace Zax\Components\Filter;

interface IPaginatorFactory {

    /** @return PaginatorControl */
    public function create();

}