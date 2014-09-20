<?php

namespace Zax\Components\Sort;

interface ISortFactory {

    /** @return SortControl */
    public function create();

}