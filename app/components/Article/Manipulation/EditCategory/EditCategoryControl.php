<?php

namespace ZaxCMS\Components\Article;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class EditCategoryControl extends Control {

	use TInjectEditCategoryFormFactory;

	protected $category;

	public function setCategory(Model\CMS\Entity\Category $category) {
		$this->category = $category;
		return $this;
	}

    public function viewDefault() {
        
    }
    
    public function beforeRender() {
        
    }

	protected function createComponentEditCategoryForm() {
	    return $this->editCategoryFormFactory->create()
		    ->setCategory($this->category)
		    ->disableAjaxFor(['form']);
	}

}