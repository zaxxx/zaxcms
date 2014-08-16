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

	/** @secured Menu, Edit */
	public function viewDefault() {

	}

	/** @secured Menu, Edit */
	public function viewDelete() {

	}

	/** @secured Menu, Edit */
	public function beforeRender() {
		$this->template->isFirst = count($this->menuService->getRepository()->getPrevSiblings($this->menuItem)) === 0;
		$this->template->isLast = count($this->menuService->getRepository()->getNextSiblings($this->menuItem)) === 0;
	}

	/** @return EditControl */
	public function getEditControl() {
		return $this->lookup('ZaxCMS\Components\Menu\EditControl');
	}

	/** @secured Menu, Edit */
	public function handleMoveUp() {
		$this->menuService->moveUp($this->menuItem);
		$this->menuService->getEm()->refresh($this->menuItem->parent);
		$this->menuService->invalidateCache();
		$this->go('this');
	}

	/** @secured Menu, Edit */
	public function handleMoveDown() {
		$this->menuService->moveDown($this->menuItem);
		$this->menuService->getEm()->refresh($this->menuItem->parent);
		$this->menuService->invalidateCache();
		$this->go('this');
	}

	public function handleClose() {
		$this->getEditControl()->go('this', ['selectItem' => NULL]);
	}

	/** @secured Menu, Edit */
	protected function createComponentEditMenuItemForm() {
		return $this->editMenuItemFormFactory->create()
		    ->setMenuItem($this->menuItem);
	}

	/** @secured Menu, Edit */
	protected function createComponentDeleteMenuItemForm() {
		return $this->deleteMenuItemFormFactory->create()
		    ->setMenuItem($this->menuItem);
	}

}