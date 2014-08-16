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

	public function attached($presenter) {
		parent::attached($presenter);
		$this->menuItem->setTranslatableLocale($this->getEditControl()->getLocale());
		$this->menuService->refresh($this->menuItem);
	}

	protected function createSubmitButtons(Form $form) {
		$form->addButtonSubmit('editMenu', 'common.button.edit', 'pencil');
		$form->addLinkSubmit('cancel', '', 'remove', $this->getEditMenuItem()->link('close!'));
		$form->enableBootstrap(['success' => ['editMenu'], 'default' => ['cancel']], TRUE);
	}

	/** @return EditMenuItemControl */
	public function getEditMenuItem() {
		return $this->lookup('ZaxCMS\Components\Menu\EditMenuItemControl');
	}

	protected function saveMenuItem(Model\Menu $menuItem, Form $form) {
		try {
			$menuItem->setTranslatableLocale($this->getEditControl()->getLocale());
			$this->menuService->getEm()->persist($menuItem);
			$this->menuService->getEm()->flush();
			$this->menuService->invalidateCache();

			$this->flashMessage('common.alert.changesSaved', 'success');
			$this->getEditControl()->go('this', ['selectItem' => $menuItem->id]);
		} catch (Kdyby\Doctrine\DuplicateEntryException $ex) {
			$form['name']->addError($this->translator->translate('form.error.duplicateEntry'));
		}
	}

}