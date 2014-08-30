<?php

namespace ZaxCMS\Components\Navigation;
use Nette,
	Zax,
	Kdyby,
	ZaxCMS\Model,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Zax\Application\UI\Control;

// TODO: DRY
class AddMenuItemControl extends Control {

	protected $addMenuItemFormFactory;

	protected $parentMenu;

	public function __construct(IAddMenuItemFormFactory $addMenuItemFormFactory) {
		$this->addMenuItemFormFactory = $addMenuItemFormFactory;
	}

	public function setParentMenu(Model\Menu $menu) {
		$this->parentMenu = $menu;
		return $this;
	}

	public function handleClose() {
		$this->getEditControl()->go('this', ['selectItem' => NULL]);
	}

	/** @return EditControl */
	public function getEditControl() {
		return $this->lookup('ZaxCMS\Components\Navigation\EditControl');
	}

	protected function createComponentAddMenuItemForm() {
		$menuItem = new Model\Menu;
		$menuItem->secured = FALSE;
	    $control = $this->addMenuItemFormFactory->create()
		    ->setMenuItem($menuItem)
		    ->setParentMenu($this->parentMenu);
		return $control;
	}

	public function viewDefault() {

	}

	public function beforeRender() {

	}

}