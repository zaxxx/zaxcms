<?php

namespace ZaxCMS\Components\Navigation;
use Nette,
	Zax,
	Kdyby,
	ZaxCMS\Model,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Zax\Application\UI\SecuredControl;

// TODO: DRY
class AddMenuItemControl extends SecuredControl {

	use TInjectAddMenuItemFormFactory,
		Model\CMS\Service\TInjectMenuService;

	protected $parentMenu;

	public function setParentMenu(Model\CMS\Entity\Menu $menu) {
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
		$menuItem = $this->menuService->create();
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