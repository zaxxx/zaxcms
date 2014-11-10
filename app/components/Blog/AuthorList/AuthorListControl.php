<?php

namespace ZaxCMS\Components\Blog;
use Nette,
    Zax,
    ZaxCMS\Model,
	ZaxCMS,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class AuthorListControl extends Zax\Components\Collections\FilterableControl {

	use Model\CMS\Service\TInjectAuthorService,
		Zax\Components\Collections\TPaginable,
		ZaxCMS\DI\TInjectArticleConfig;

	protected function createQueryObject() {
		return new Model\CMS\Query\AuthorQuery;
	}

	protected function getService() {
		return $this->authorService;
	}

    public function viewDefault() {
        
    }
    
    public function beforeRender() {
	    $rs = $this->getFilteredResultSet();
        $this->template->authors = $rs;
	    $this->template->c = $this->articleConfig->getConfig();
    }

}