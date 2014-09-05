<?php

namespace Zax\Components\Collection;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

abstract class PaginatedCollectionControl extends CollectionControl {

	/** @var Zax\Components\Filter\IPaginatorFactory */
	protected $paginatorFactory;

	public function attached($presenter) {
		parent::attached($presenter);
		$this->getPaginator();
	}

	public function injectPaginatorFactory(Zax\Components\Filter\IPaginatorFactory $paginatorFactory) {
		$this->paginatorFactory = $paginatorFactory;
	}

	protected function createComponentPaginator() {
	    return $this->paginatorFactory->create()
		    ->setResultSet($this->getResultSet());
	}

	/** @return Zax\Components\Filter\PaginatorControl */
	protected function getPaginator() {
		return $this['paginator'];
	}

}