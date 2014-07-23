<?php

namespace ZaxCMS\Components\Menu;
use Nette,
	Zax,
	Kdyby,
	ZaxCMS\Model,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Zax\Application\UI\Control;

class EditMenuItemControl extends Control {

	protected $menuItem;

	protected $editMenuItemFormFactory;

	protected $deleteMenuItemFormFactory;

	protected $menuService;

	public function __construct(IEditMenuItemFormFactory $editMenuItemFormFactory,
	                            IDeleteMenuItemFormFactory $deleteMenuItemFormFactory,
								Model\MenuService $menuService) {
		$this->editMenuItemFormFactory = $editMenuItemFormFactory;
		$this->deleteMenuItemFormFactory = $deleteMenuItemFormFactory;
		$this->menuService = $menuService;
	}

	public function setMenuItem(Model\Menu $menuItem) {
		$this->menuItem = $menuItem;
		return $this;
	}

	public function viewDefault() {

	}
	
	public function viewDelete() {

	}

	public function beforeRender() {

	}

	/** @return EditControl */
	public function getEditControl() {
		return $this->lookup('ZaxCMS\Components\Menu\EditControl');
	}

	public function handleMoveUp() {
		$this->menuService->moveUp($this->menuItem);
		$this->menuService->getEm()->refresh($this->menuItem->parent);
		$this->menuService->invalidateCache();
		$this->go('this');
	}

	public function handleMoveDown() {
		$this->menuService->moveDown($this->menuItem);
		$this->menuService->getEm()->refresh($this->menuItem->parent);
		$this->menuService->invalidateCache();
		$this->go('this');
	}

	public function handleClose() {
		$this->getEditControl()->go('this', ['selectItem' => NULL]);
	}

	protected function createComponentEditMenuItemForm() {
	    $control = $this->editMenuItemFormFactory->create()
		    ->setMenuItem($this->menuItem);
		if($this->ajaxEnabled) {
			$control->enableAjax(!$this->autoAjax);
		}
		return $control;
	}

	protected function createComponentDeleteMenuItemForm() {
	    $control = $this->deleteMenuItemFormFactory->create()
		    ->setMenuItem($this->menuItem);
		if($this->ajaxEnabled) {
			$control->enableAjax(!$this->autoAjax);
		}
		return $control;
	}

}