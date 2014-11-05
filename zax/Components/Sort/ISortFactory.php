<?php

namespace Zax\Components\Sort;

interface ISortFactory {

    /** @return SortControl */
    public function create();

}



trait TInjectSortFactory {

	protected $sortFactory;

	public function injectSortFactory(ISortFactory $sortFactory) {
		$this->sortFactory = $sortFactory;
	}

}

