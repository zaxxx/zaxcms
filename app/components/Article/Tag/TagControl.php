<?php

namespace ZaxCMS\Components\Article;
use Nette,
    Zax,
    ZaxCMS\Model,
	ZaxCMS,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class TagControl extends Control {

	use TInjectArticleListFactory,
		Model\CMS\Service\TInjectTagService,
		ZaxCMS\DI\TInjectArticleConfig;

	/** @persistent */
	public $slug;

	protected $tag;

    public function viewDefault() {
        
    }
    
    public function beforeRender() {
        
    }

	protected function getSlug() {
		return (string)$this->slug;
	}

	public function getTag() {
		if($this->tag === NULL) {
			$tag = $this->tagService->getBy(['slug' => $this->getSlug()]);
			if($tag === NULL) {
				throw new Nette\Application\BadRequestException;
			}
			$tag->setTranslatableLocale($this->getLocale());
			$this->tagService->refresh($tag);
			$this->tag = $tag;
		}
		return $this->tag;
	}

	protected function createComponentArticleList() {
	    return $this->articleListFactory->create()
		    ->setTag($this->getTag())
		    ->enablePaginator($this->articleConfig->getListItemsPerPage());
	}

}