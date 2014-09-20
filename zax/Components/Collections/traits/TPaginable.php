<?php

namespace Zax\Components\Collections;
use Zax,
	Nette;

trait TPaginable {

	/** @var Zax\Components\Paginator\IPaginatorFactory */
	protected $paginatorFactory;

	public function injectPaginator(Zax\Components\Paginator\IPaginatorFactory $paginatorFactory) {
		$this->paginatorFactory = $paginatorFactory;
	}

	protected function createComponentPaginator() {
		return $this->paginatorFactory->create();
	}

	public function enablePaginator($itemsPerPage = 30) {
		$this['paginator']->setItemsPerPage($itemsPerPage);
		return $this;
	}

}