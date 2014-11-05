<?php

namespace ZaxCMS\Components\Navigation;
use Nette,
	Zax,
	Kdyby,
	Gedmo,
	ZaxCMS\Model,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Zax\Application\UI\SecuredControl;

class EditMenuItemControl extends SecuredControl {

	use TInjectEditMenuItemFormFactory,
		TInjectDeleteMenuItemFormFactory,
		Model\CMS\Service\TInjectMenuService;

	protected $menuItem;

	public function setMenuItem(Model\CMS\Entity\Menu $menuItem) {
		$this->menuItem = $menuItem;
		return $this;
	}

	/** @secured Menu, Edit */
	public function viewDefault() {
		try {
			$this->template->isFirst = count($this->menuService->getRepository()->getPrevSiblings($this->menuItem)) === 0;
			$this->template->isLast = count($this->menuService->getRepository()->getNextSiblings($this->menuItem)) === 0;
		} catch (Gedmo\Exception\InvalidArgumentException $ex) {
			$this->template->isFirst = FALSE;
			$this->template->isLast = FALSE;
		}
	}

	/** @secured Menu, Edit */
	public function viewDelete() {
		$this->template->menuItem = $this->menuItem;
	}

	/** @secured Menu, Edit */
	public function beforeRender() {

	}

	/** @return EditControl */
	public function getEditControl() {
		return $this->lookup('ZaxCMS\Components\Navigation\EditControl');
	}

	/** @secured Menu, Edit */
	public function handleMoveUp() {
		$this->menuService->moveUp($this->menuItem);
		$this->menuService->refresh($this->menuItem->parent);
		$this->go('this');
	}

	/** @secured Menu, Edit */
	public function handleMoveDown() {
		$this->menuService->moveDown($this->menuItem);
		$this->menuService->refresh($this->menuItem->parent);
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