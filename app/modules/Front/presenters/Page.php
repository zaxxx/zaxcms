<?php

namespace ZaxCMS\FrontModule;
use Nette,
	ZaxCMS,
	ZaxCMS\Model,
    Nette\Application\UI\Presenter,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax;

class PagePresenter extends BasePresenter {

	protected $pageService;

	protected $page;

	public function __construct(Model\CMS\Service\PageService $pageService) {
		$this->pageService = $pageService;
	}

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