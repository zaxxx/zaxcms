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

	public function setMenu(Model\Menu $menu) {
		$this->menu = $menu;
		return $this;
	}

	/** @return EditControl */
	public function getEditControl() {
		return $this->lookup('ZaxCMS\Components\Menu\EditControl');
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