<?php

namespace ZaxCMS\Components\Menu;
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

	public function setParentMenu(Model\Menu $parentMenu) {
		$this->parentMenu = $parentMenu;
		return $this;
	}

    protected function createSubmitButtons(Form $form) {
	    $form->addButtonSubmit('addMenu', 'common.button.edit', 'pencil');
	    $form->addLinkSubmit('cancel', '', 'remove', $this->link('close!'));
	    $form->enableBootstrap(['success' => ['addMenu'], 'default' => ['cancel']], TRUE);
    }

	/** @return AddMenuItemControl */
	public function getAddMenuItem() {
		return $this->lookup('ZaxCMS\Components\Menu\AddMenuItemControl');
	}

	protected function saveMenuItem(Model\Menu $menuItem, Form $form) {
		try {
			$menuItem->setTranslatableLocale($this->getEditControl()->getLocale());
			$this->menuService->getRepository()->persistAsLastChildOf($menuItem, $this->parentMenu);
			$this->menuService->getEm()->flush();
			$this->menuService->invalidateCache();

			$this->flashMessage('menu.alert.newEntrySaved');
			$this->getEditControl()->go('this', ['selectItem' => $menuItem->id]);
		} catch (Kdyby\Doctrine\DuplicateEntryException $ex) {
			$form['name']->addError($this->translator->translate('form.error.duplicateEntry'));
		}
	}

}