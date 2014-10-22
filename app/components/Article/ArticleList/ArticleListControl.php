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

	protected $categories;

	protected $tag;

	public function __construct(Model\CMS\Service\ArticleService $articleService) {
		$this->articleService = $articleService;
	}

	public function setCategories($categories) {
		$this->categories = $categories;
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
			->inCategories($this->categories)
			->withTag($this->tag);
	}

    public function viewDefault() {
        
    }
    
    public function beforeRender() {
        $this->template->articles = $this->getFilteredResultSet();
    }

}