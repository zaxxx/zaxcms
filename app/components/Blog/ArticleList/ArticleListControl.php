<?php

namespace ZaxCMS\Components\Blog;
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
		ZaxCMS\DI\TInjectArticleConfig,
		TInjectPublishButtonFactory;

	protected $category;

	protected $categories;

	protected $tag;

	protected $author;

	protected $search;

	protected $searchTitleOnly = FALSE;

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

	public function setAuthor(Model\CMS\Entity\Author $author) {
		$this->author = $author;
		return $this;
	}

	public function setSearch($s, $titleOnly = FALSE) {
		$this->search = $s;
		$this->searchTitleOnly = $titleOnly;
		return $this;
	}

	protected function getService() {
		return $this->articleService;
	}

	protected function createQueryObject() {
		return (new Model\CMS\Query\ArticleQuery($this->getLocale()))
			->inCategories($this->categories)
			->withTag($this->tag)
			->byAuthor($this->author)
			->search($this->search, $this->searchTitleOnly)
			->publicOnly(!$this->user->isAllowed('WebContent', 'Edit'))
			->addRootCategoryFilter($this->category, $this->user->isAllowed('WebContent', 'Edit'));
	}

    public function viewDefault() {
        $this->template->isRootCategory = $this->category !== NULL && $this->category->depth === 0;
    }
    
    public function beforeRender($category = NULL) {
        $this->template->articles = $this->getFilteredResultSet();
	    $this->template->c = $this->articleConfig->getConfig();
	    $this->template->category = $category;
    }

	protected function createComponentPublishButton() {
		return new NetteUI\Multiplier(function($id) {
			$button = $this->publishButtonFactory->create()
				->setArticleId($id);
			if($this->isAjaxEnabled()) {
				$button->enableAjax();
			}
			$button->onPublish[] = function(Model\CMS\Entity\Article $article) {
				$this->go('this');
			};

			return $button;
		});

	}

}