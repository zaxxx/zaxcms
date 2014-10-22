<?php

namespace ZaxCMS\Components\Category;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class CategoryControl extends Control {

	protected $category;

	public function setCategory(Model\CMS\Entity\Category $category) {
		$this->category = $category;
		return $this;
	}

    public function viewDefault() {
        
    }
    
    public function beforeRender() {
        $this->template->category = $this->category;
    }

}