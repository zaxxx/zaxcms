<?php

namespace ZaxCMS\AdminModule;
use Nette,
	Zax\Application\UI,
	ZaxCMS,
	Zax;

class PagesPresenter extends BasePresenter {

	protected $pagesFactory;

	public function __construct(ZaxCMS\AdminModule\Components\Pages\IPagesFactory $pagesFactory) {
		$this->pagesFactory = $pagesFactory;
	}

	protected function createComponentPages() {
	    return $this->pagesFactory->create()
		    ->enableAjax();
	}

}