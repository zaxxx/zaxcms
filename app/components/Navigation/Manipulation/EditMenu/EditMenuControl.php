<?php

namespace ZaxCMS\Components\Navigation;
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

	public function setMenu(Model\Menu $menu) {
		$this->menu = $menu;
		return $this;
	}

	public function handleClose() {
		$this->getEditControl()->close();
	}

	/** @return EditControl */
	public function getEditControl() {
		return $this->lookup('ZaxCMS\Components\Navigation\EditControl');
	}

	/** @secured Menu, Edit */
	protected function createComponentEditMenuForm() {
	    return $this->editMenuFormFactory->create()
		    ->setMenu($this->menu);
	}

	/** @secured Menu, Edit */
	public function viewDefault() {

	}

	/** @secured Menu, Edit */
	public function beforeRender() {

	}

}