<?php

namespace ZaxCMS\Components\Article;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class ArticleListControl extends Zax\Components\Collections\FilterableControl {

	use Zax\Components\Collections\TPaginable;

	protected $articleService;

	protected $category;

	protected $tag;

	public function __construct(Model\CMS\Service\ArticleService $articleService) {
		$this->articleService = $articleService;
	}

	public function setCategory(Model\CMS\Entity\Category $category) {
		$this->category = $category;
		return $this;
	}

	public function setTag(Model\CMS\Entity\Tag $tag) {
		$this->tag = $tag;
		return $this;
	}

	protected function getService() {
		return $this->articleService;
	}

	protected function createQueryObject() {
		return (new Model\CMS\Query\ArticleQuery($this->getLocale()))
			->inCategory($this->category)
			->withTag($this->tag);
	}

    public function viewDefault() {
        
    }
    
    public function beforeRender() {
        $this->template->articles = $this->getFilteredResultSet();
    }

}