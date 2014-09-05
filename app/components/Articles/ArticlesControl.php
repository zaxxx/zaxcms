<?php

namespace ZaxCMS\Components\Articles;
use Nette,
	Kdyby,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI;

class ArticlesControl extends Zax\Components\Collection\PaginatedCollectionControl {

	protected $pageService;

	public function __construct(Model\CMS\Service\PageService $pageService) {
		$this->pageService = $pageService;
	}

	public function getResultSet() {
		return $this->pageService->fetchQueryObject(new Model\CMS\Query\PageQuery);
	}

    public function viewDefault() {
        
    }
    
    public function beforeRender() {
        $this->template->articles = $this->getPaginator()->getFilteredResultSet();
    }

}