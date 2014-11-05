<?php

namespace ZaxCMS\Components\Article;
use Nette,
    Zax,
    ZaxCMS\Model,
	ZaxCMS,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class CategoryControl extends Control {

	use TInjectArticleListFactory,
		Model\CMS\Service\TInjectCategoryService,
		ZaxCMS\DI\TInjectArticleConfig;

	/** @persistent */
	public $slug;

	protected $category;

	protected function getSlug() {
		return (string)$this->slug;
	}

	public function getCategory() {
		if($this->category === NULL) {
			$category = $this->categoryService->getBy(['slug' => $this->getSlug()]);
			if($category === NULL) {
				throw new Nette\Application\BadRequestException;
			}
			$category->setTranslatableLocale($this->getLocale());
			$this->categoryService->refresh($category);
			$this->category = $category;
		}
		return $this->category;
	}

    public function viewDefault() {

    }
    
    public function beforeRender() {
	    $this->template->category = $this->getCategory();
	    $this->template->ancestors = $this->categoryService->findPath($this->getCategory());
	    $this->template->articleConfig = $this->articleConfig;
    }

	protected function createComponentArticleList() {
		$children = $this->categoryService->getRepository()->getChildren($this->getCategory(), FALSE, NULL, 'asc', TRUE);
		return $this->articleListFactory->create()
			->enablePaginator($this->articleConfig->getListItemsPerPage())
			->setMainCategory($this->getCategory())
			->setCategories($children);
	}

}