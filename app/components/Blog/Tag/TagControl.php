<?php

namespace ZaxCMS\Components\Blog;
use Nette,
    Zax,
    ZaxCMS\Model,
	ZaxCMS,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

class TagControl extends SecuredControl {

	use TInjectArticleListFactory,
		TInjectEditTagFactory,
		Model\CMS\Service\TInjectTagService,
		ZaxCMS\DI\TInjectArticleConfig;

	/** @persistent */
	public $slug;

	protected $tag;

    public function viewDefault() {
        
    }

	/** @secured WebContent, Edit */
	public function viewEdit() {
		$this['editTag'];
	}
    
    public function beforeRender() {
        $this->template->c = $this->articleConfig->getConfig();
	    $this->template->tag = $this->getTag();
    }

	protected function createTexy() {
		$texy = parent::createTexy();
		$texy->headingModule->top = 2;
		return $texy;
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

	protected function createComponentEditTag() {
	    return $this->editTagFactory->create()
		    ->setTag($this->getTag());
	}

}