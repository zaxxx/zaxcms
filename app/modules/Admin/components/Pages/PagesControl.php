<?php

namespace ZaxCMS\AdminModule\Components\Pages;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

class PagesControl extends SecuredControl {

	protected $pageService;

	protected $addPageFormFactory;

	public function __construct(Model\PageService $pageService,
								IAddPageFormFactory $addPageFormFactory) {
		$this->pageService = $pageService;
		$this->addPageFormFactory = $addPageFormFactory;
	}

    public function viewDefault() {
        
    }

	public function viewAdd() {

	}
    
    public function beforeRender() {
        $this->template->pages = $this->pageService->findAll();
    }

	protected function createComponentAddPageForm() {
	    return $this->addPageFormFactory->create()
		    ->setPage(new Model\Page);
	}

}