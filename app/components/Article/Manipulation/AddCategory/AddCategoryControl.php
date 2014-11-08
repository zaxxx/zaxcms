<?php

namespace ZaxCMS\Components\Article;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class AddCategoryControl extends Control {

	use Model\CMS\Service\TInjectCategoryService,
		TInjectAddCategoryFormFactory;

	protected $category;

	public function setParentCategory(Model\CMS\Entity\Category $category) {
		$this->category = $category;
		return $this;
	}

    public function viewDefault() {
        
    }
    
    public function beforeRender() {
        
    }

	protected function createComponentAddCategoryForm() {
		$category = $this->categoryService->create();
		$category->parent = $this->category;
	    return $this->addCategoryFormFactory->create()
		    ->setCategory($category)
		    ->disableAjaxFor(['form']);
	}

}