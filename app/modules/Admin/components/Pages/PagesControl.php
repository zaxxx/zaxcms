<?php

namespace ZaxCMS\AdminModule\Components\Pages;
use Nette,
    Zax,
	ZaxCMS,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

class PagesControl extends SecuredControl {

	/** @persistent */
	public $page;

	protected $pageEntity;

	protected $pageService;

	protected $addPageFormFactory;

	protected $editPageFormFactory;

	protected $deletePageFormFactory;

	protected $webContentFactory;

	public function __construct(Model\PageService $pageService,
								IAddPageFormFactory $addPageFormFactory,
								IEditPageFormFactory $editPageFormFactory,
								IDeletePageFormFactory $deletePageFormFactory,
								ZaxCMS\Components\WebContent\IWebContentFactory $webContentFactory) {
		$this->pageService = $pageService;
		$this->addPageFormFactory = $addPageFormFactory;
		$this->editPageFormFactory = $editPageFormFactory;
		$this->deletePageFormFactory = $deletePageFormFactory;
		$this->webContentFactory = $webContentFactory;
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

	protected function getPage() {
		if($this->page === NULL) {
			return NULL;
		}
		if($this->pageEntity === NULL) {
			$this->pageEntity = $this->pageService->getByName($this->page);
		}
		return $this->pageEntity;
	}
    
    public function beforeRender() {
        $this->template->pages = $this->pageService->findAll();
	    if($page = $this->getPage()) {
		    $page->setTranslatableLocale($this->getLocale());
		    $this->pageService->refresh($page);
		    $this->template->page = $page;
	    }
    }

	/** @secured Pages, Edit */
	protected function createComponentWebContent() {
		return new Nette\Application\UI\Multiplier(function($name) {
			return $this->webContentFactory->create()
				->setCacheNamespace('ZaxCMS.WebContent.' . $name)
				->enableAjax(TRUE)
				->setName($name);
		});
	}

	/** @secured Pages, Add */
	protected function createComponentAddPageForm() {
	    return $this->addPageFormFactory->create()
		    ->setPage(new Model\Page);
	}

	/** @secured Pages, Edit */
	protected function createComponentEditPageForm() {
	    return $this->editPageFormFactory->create()
		    ->setPage($this->getPage());
	}

	/** @secured Pages, Delete */
	protected function createComponentDeletePageForm() {
	    return $this->deletePageFormFactory->create()
		    ->setPage($this->getPage());
	}

}