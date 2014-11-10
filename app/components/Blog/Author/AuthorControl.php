<?php

namespace ZaxCMS\Components\Blog;
use Nette,
    Zax,
    ZaxCMS\Model,
	ZaxCMS,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

class AuthorControl extends SecuredControl {

	use Model\CMS\Service\TInjectAuthorService,
		TInjectArticleListFactory,
		ZaxCMS\DI\TInjectArticleConfig,
		TInjectEditAuthorFactory;

	/** @persistent */
	public $slug;

	protected $author;

	protected function getSlug() {
		return (string)$this->slug;
	}

	public function getAuthor() {
		if($this->author === NULL) {
			$author = $this->authorService->getBy(['slug' => $this->getSlug()]);
			if($author === NULL) {
				throw new Nette\Application\BadRequestException;
			}
			$this->author = $author;
		}
		return $this->author;
	}

    public function viewDefault() {
        
    }

	/** @secured WebContent, Edit */
	public function viewEdit() {
		$this['editAuthor'];
	}
    
    public function beforeRender() {
	    $this->template->author = $this->getAuthor();
        $this->template->c = $this->articleConfig->getConfig();
    }

	protected function createComponentArticleList() {
	    return $this->articleListFactory->create()
		    ->setAuthor($this->getAuthor())
		    ->enablePaginator($this->articleConfig->getListItemsPerPage());
	}

	/** @secured WebContent, Edit */
	protected function createComponentEditAuthor() {
	    return $this->editAuthorFactory->create()
		    ->setAuthor($this->getAuthor());
	}

}