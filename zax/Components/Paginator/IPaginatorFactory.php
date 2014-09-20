<?php

namespace Zax\Components\Paginator;

interface IPaginatorFactory {

    /** @return PaginatorControl */
    public function create();

}