<?php

namespace ZaxCMS\AdminModule\Components\Users;
use Nette,
	Kdyby,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class UserSearchControl extends Control implements Zax\Model\Doctrine\IQueryObjectFilter {

	/** @persistent */
	public $search;

	protected $formFactory;

	public function __construct(ZaxUI\FormFactory $formFactory) {
		$this->formFactory = $formFactory;
	}

	public function filterQueryObject(Kdyby\Doctrine\QueryObject $queryObject) {
		/** @var Model\CMS\Query\UserQuery $queryObject */
		if($this->search !== NULL) {
			return $queryObject->search($this->search);
		}
		return $queryObject;
	}

	protected function createComponentSearchForm() {
	    $form = $this->formFactory->create();

		$form->addText('search')
			->setDefaultValue($this->search);

		$form->addButtonSubmit('find', '', 'search');

		$form->onSuccess[] = function($form, $values) {
			$this->go('this', ['search' => $values->search]);
			$this->redrawNothing();
			$this->parent->redrawControl('list');
			$this->parent['userSort']->redrawControl();
			$this->parent['roleFilter']->redrawControl();
		};

		return $form;
	}

    public function viewDefault() {
        
    }
    
    public function beforeRender() {
        
    }

}