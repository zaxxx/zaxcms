<?php

namespace ZaxCMS\Components\Navigation;
use Nette,
	Kdyby,
    Zax,
    ZaxCMS\Model,
    Nette\Forms\Form,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control,
    Zax\Application\UI\FormControl;

class AddMenuItemFormControl extends MenuItemFormControl {

	protected $parentMenu;

	public function setParentMenu(Model\CMS\Entity\Menu $parentMenu) {
		$this->parentMenu = $parentMenu;
		return $this;
	}

    protected function createSubmitButtons(Form $form) {
	    $form->addButtonSubmit('addMenu', 'common.button.add', 'pencil');
	    $form->addLinkSubmit('cancel', '', 'remove', $this->getAddMenuItem()->link('close!'));
	    $form->enableBootstrap(['success' => ['addMenu'], 'default' => ['cancel']], TRUE);
    }

	/** @return AddMenuItemControl */
	public function getAddMenuItem() {
		return $this->lookup('ZaxCMS\Components\Navigation\AddMenuItemControl');
	}

	protected function saveMenuItem(Model\CMS\Entity\Menu $menuItem, Form $form) {
		$menuItem->setTranslatableLocale($this->getEditControl()->getLocale());
		$this->menuService->getRepository()->persistAsLastChildOf($menuItem, $this->parentMenu);
		$this->menuService->flush();

		$this->flashMessage('common.alert.newEntrySaved', 'success');
		$this->getEditControl()->go('this', ['selectItem' => $menuItem->id]);
	}

}