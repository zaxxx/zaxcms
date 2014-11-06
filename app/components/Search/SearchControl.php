<?php

namespace ZaxCMS\Components\Search;
use Nette,
    Zax,
    ZaxCMS\Model,
	ZaxCMS,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class SearchControl extends Control {

	use ZaxCMS\DI\TInjectSearchConfig,
		ZaxCMS\Components\Article\TInjectArticleListFactory,
		TInjectSearchFormFactory;

	/** @persistent */
	public $q;

	/** @persistent */
	public $filters = [];

    public function viewDefault() {
        
    }
    
    public function beforeRender() {
        $this->template->searchConfig = $this->searchConfig;
    }

	protected function createComponentArticleList() {
		if($this->searchConfig->getArticlesEnabled()) {
		    return $this->articleListFactory->create()
			    ->setSearch($this->q, FALSE)
			    ->enablePaginator($this->searchConfig->getArticlesPerPage());
		}
	}
	
	protected function createComponentSearchForm() {
	    return $this->searchFormFactory->create()
		    ->setSearch($this->q);
	}

}