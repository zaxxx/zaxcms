<?php

namespace ZaxCMS\Components\Menu;
use Nette,
	Zax,
	ZaxCMS\Model,
	Kdyby,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Zax\Application\UI\Control;

class EditMenuControl extends Control {

	protected $menu;

	protected $editMenuFormFactory;

	public function __construct(IEditMenuFormFactory $editMenuFormFactory) {
		$this->editMenuFormFactory = $editMenuFormFactory;
	}

	public function createForm() {
		return $this->formFactory->create();
	}

	public function setMenu(Model\Menu $menu) {
		$this->menu = $menu;
		return $this;
	}

	protected function createComponentEditMenuForm() {
	    return $this->editMenuFormFactory->create()
		    ->setMenu($this->menu);
	}

	public function viewDefault() {

	}

	public function beforeRender() {

	}

}