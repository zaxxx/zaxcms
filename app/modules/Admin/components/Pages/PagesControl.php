<?php

namespace ZaxCMS\AdminModule\Components\Pages;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

class PagesControl extends SecuredControl {

	/** @persistent */
	public $page;

	protected $pageService;

	protected $addPageFormFactory;

	protected $editPageFormFactory;

	protected $deletePageFormFactory;

	public function __construct(Model\PageService $pageService,
								IAddPageFormFactory $addPageFormFactory,
								IEditPageFormFactory $editPageFormFactory,
								IDeletePageFormFactory $deletePageFormFactory) {
		$this->pageService = $pageService;
		$this->addPageFormFactory = $addPageFormFactory;
		$this->editPageFormFactory = $editPageFormFactory;
		$this->deletePageFormFactory = $deletePageFormFactory;
	}

    public function viewDefault() {
        
    }

	/** @secured Pages, Add */
	public function viewAdd() {

	}

	/** @secured Pages, Edit */
	public function viewEdit() {

	}

	/** @secured Pages, Delete */
	public function viewDelete() {

	}
    
    public function beforeRender() {
        $this->template->pages = $this->pageService->findAll();
    }

	/** @secured Pages, Add */
	protected function createComponentAddPageForm() {
	    return $this->addPageFormFactory->create()
		    ->setPage(new Model\Page);
	}

	/** @secured Pages, Edit */
	protected function createComponentEditPageForm() {
	    return $this->editPageFormFactory->create()
		    ->setPage($this->pageService->getByName($this->page));
	}

	/** @secured Pages, Delete */
	protected function createComponentDeletePageForm() {
	    return $this->deletePageFormFactory->create()
		    ->setPage($this->pageService->getByName($this->page));
	}

}