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

	protected $addArticleFactory;

	protected $category;

	protected $categories;

	protected $tag;

	public function __construct(Model\CMS\Service\ArticleService $articleService,
								IAddArticleFactory $addArticleFactory) {
		$this->articleService = $articleService;
		$this->addArticleFactory = $addArticleFactory;
	}

	public function setMainCategory(Model\CMS\Entity\Category $category) {
		$this->category = $category;
		return $this;
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
			->withTag($this->tag)
			->publicOnly(!$this->user->isAllowed('WebContent', 'Edit'));
	}

    public function viewDefault() {
        $this->template->showAddButton = $this->category !== NULL;
    }

	/** @secured WebContent, Edit */
	public function viewAdd() {

	}
    
    public function beforeRender() {
        $this->template->articles = $this->getFilteredResultSet();
    }

	protected function createComponentAddArticle() {
	    return $this->addArticleFactory->create()
		    ->setCategory($this->category);
	}

}