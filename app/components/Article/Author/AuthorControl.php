<?php

namespace ZaxCMS\Components\Article;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class AuthorControl extends Control {

	use Model\CMS\Service\TInjectAuthorService,
		TInjectArticleListFactory;

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
    
    public function beforeRender() {
        
    }

	protected function createComponentArticleList() {
	    return $this->articleListFactory->create()
		    ->setAuthor($this->getAuthor());
	}

}