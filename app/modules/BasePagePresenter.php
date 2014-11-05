<?php

namespace ZaxCMS;
use Nette,
	ZaxCMS,
	ZaxCMS\Model,
	Nette\Application\UI\Presenter,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Zax;

abstract class BasePagePresenter extends BasePresenter {

	use Model\CMS\Service\TInjectPageService;

	protected $page;

	public function actionDefault($page) {
		$page = $this->pageService->getByName($page);
		if($page === NULL) {
			throw new Nette\Application\BadRequestException;
		}
		$page->setTranslatableLocale($this->getLocale());
		$this->pageService->refresh($page);
		$this->page = $page;
	}

	public function renderDefault($page) {
		$this->template->page = $this->page;
	}

}