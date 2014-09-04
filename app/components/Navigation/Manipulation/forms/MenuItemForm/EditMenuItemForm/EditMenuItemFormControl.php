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
		return $this->lookup('ZaxCMS\Components\Navigation\EditMenuItemControl');
	}

	protected function saveMenuItem(Model\CMS\Entity\Menu $menuItem, Form $form) {
		$menuItem->setTranslatableLocale($this->getEditControl()->getLocale());
		$this->menuService->persist($menuItem);
		$this->menuService->flush();

		$this->flashMessage('common.alert.changesSaved', 'success');
		$this->getEditControl()->go('this', ['selectItem' => $menuItem->id]);
	}

}