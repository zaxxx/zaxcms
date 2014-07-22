<?php

namespace ZaxCMS\Components\Menu;
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
		$this->parent->go('this', ['selectItem' => NULL]);
	}

	protected function createComponentAddMenuItemForm() {
		$menuItem = new Model\Menu;
		$menuItem->isMenuItem = TRUE;
		$menuItem->secured = FALSE;
	    return $this->addMenuItemFormFactory->create()
		    ->setMenuItem($menuItem)
		    ->setParentMenu($this->parentMenu);
	}

	public function viewDefault() {

	}

	public function beforeRender() {

	}

}