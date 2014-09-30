<?php

namespace ZaxCMS\AdminModule\Components\Roles;
use Nette,
    Zax,
    ZaxCMS\Model,
    Nette\Forms\Form,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control,
    Zax\Application\UI\FormControl;

class EditRoleFormControl extends RoleFormControl {

	/** @secured Roles, Edit */
	public function viewDefault() {}

	/** @secured Roles, Edit */
	public function beforeRender() {}

	protected function createSubmitButtons(Form $form) {
		$form->addButtonSubmit('saveRole', 'role.button.editRole', 'pencil');
		$form->addLinkSubmit('cancel', '', 'remove', $this->link('cancel!'));
		$form->enableBootstrap(['primary' => ['saveRole'], 'default' => ['cancel']], TRUE);
	}

	protected function successFlashMessage() {
		$this->flashMessage('common.alert.changesSaved', 'success');
	}

}