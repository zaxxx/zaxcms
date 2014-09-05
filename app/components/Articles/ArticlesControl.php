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
		$query = $this->pageService->createQueryBuilder()
			->select('a')
			->from(Model\CMS\Entity\Page::getClassName(), 'a')
			->getQuery();
		$rs = new Kdyby\Doctrine\ResultSet($query);
		return $rs;
	}

    public function viewDefault() {
        
    }
    
    public function beforeRender() {
        $this->template->articles = $this->getPaginator()->getFilteredResultSet();
    }

}