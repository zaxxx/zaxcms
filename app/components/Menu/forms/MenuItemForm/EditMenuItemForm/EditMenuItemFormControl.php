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

class EditMenuItemFormControl extends MenuItemFormControl {

	protected function createSubmitButtons(Form $form) {
		$form->addButtonSubmit('editMenu', 'common.button.edit', 'pencil');
		$form->addLinkSubmit('cancel', '', 'remove', $this->link('close!'));
		$form->enableBootstrap(['success' => ['editMenu'], 'default' => ['cancel']], TRUE);
	}

	protected function saveMenuItem(Model\Menu $menuItem, Form $form) {
		try {
			$this->menuService->getEm()->persist($menuItem);
			$this->menuService->getEm()->flush();

			$this->flashMessage('menu.alert.changesSaved');
			$this->lookup('ZaxCMS\Components\Menu\EditControl')->go('this', ['selectItem' => $menuItem->id]);
		} catch (Kdyby\Doctrine\DuplicateEntryException $ex) {
			$form['name']->addError($this->translator->translate('form.error.duplicateEntry'));
		}
	}

}