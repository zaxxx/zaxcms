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
		ZaxCMS\DI\TInjectArticleConfig;

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
			->publicOnly(!$this->user->isAllowed('WebContent', 'Edit'))
			->byAuthor($this->author)
			->search($this->search, $this->searchTitleOnly);
	}

    public function viewDefault() {
        $this->template->isSpecificCategory = $this->category !== NULL;
    }
    
    public function beforeRender() {
        $this->template->articles = $this->getFilteredResultSet();
	    $this->template->articleConfig = $this->articleConfig;
    }



}