<?php

namespace ZaxCMS;
use Nette,
	ZaxCMS,
	ZaxCMS\Model,
	Nette\Application\UI\Presenter,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Zax;

abstract class BaseSearchPresenter extends BasePresenter {

	use ZaxCMS\Components\Search\TInjectSearchFactory;

	protected function createComponentSearch() {
	    return $this->searchFactory->create();
	}

}
