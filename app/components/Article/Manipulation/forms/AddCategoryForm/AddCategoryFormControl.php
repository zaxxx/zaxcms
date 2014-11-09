<?php

namespace ZaxCMS\Components\Article;
use Nette,
    Zax,
    ZaxCMS\Model,
    Nette\Forms\Form,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control,
    Zax\Application\UI\FormControl;

class AddCategoryFormControl extends CategoryFormControl {

	public function handleCancel() {
		$this->parent->parent->go('this', ['view' => 'Default']);
	}

}