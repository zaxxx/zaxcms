<?php

namespace Zax\Components\Paginator;

interface IPaginatorFactory {

    /** @return PaginatorControl */
    public function create();

}


trait TInjectPaginatorFactory {

	protected $paginatorFactory;

	public function injectPaginatorFactory(IPaginatorFactory $paginatorFactory) {
		$this->paginatorFactory = $paginatorFactory;
	}

}

