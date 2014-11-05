<?php

namespace ZaxCMS;
use Nette,
	ZaxCMS,
	ZaxCMS\Model,
	Nette\Application\UI\Presenter,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Zax;

abstract class BaseCategoryPresenter extends BasePresenter {

	use ZaxCMS\Components\Article\TInjectCategoryFactory;
	
	protected function createComponentCategory() {
	    return $this->categoryFactory->create();
	}

}
