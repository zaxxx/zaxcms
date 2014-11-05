<?php

namespace ZaxCMS\Components\Article;
use Nette,
    Zax,
    ZaxCMS\Model,
	ZaxCMS,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class ArticleListControl extends Zax\Components\Collections\FilterableControl {

	use Zax\Components\Collections\TPaginable,
		Model\CMS\Service\TInjectArticleService,
		TInjectAddArticleFactory,
		TInjectAddCategoryFactory,
		ZaxCMS\DI\TInjectArticleConfig;

	protected $category;

	protected $categories;

	protected $tag;

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
        $this->template->isSpecificCategory = $this->category !== NULL;
    }

	/** @secured WebContent, Edit */
	public function viewAdd() {
		$this['addArticle'];
	}

	/** @secured WebContent, Edit */
	public function viewAddCategory() {

	}
    
    public function beforeRender() {
        $this->template->articles = $this->getFilteredResultSet();
	    $this->template->articleConfig = $this->articleConfig;
    }

	protected function createComponentAddArticle() {
	    return $this->addArticleFactory->create()
		    ->setCategory($this->category)
		    ->enableAjax();
	}

	protected function createComponentAddCategory() {
	    return $this->addCategoryFactory->create()
		    ->setParentCategory($this->category);
	}

}