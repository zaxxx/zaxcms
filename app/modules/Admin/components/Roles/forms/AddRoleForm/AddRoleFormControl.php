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

class AddRoleFormControl extends RoleFormControl {

	public function setRole(Model\CMS\Entity\Role $role) {
		if(!$role->parent->canBeInheritedFrom()) {
			throw new Model\CMS\ProtectedRoleException('You cannot inherit from this role.');
		}
		return parent::setRole($role);
	}

    protected function createSubmitButtons(Form $form) {
	    $form->addButtonSubmit('saveRole', 'role.button.addRole', 'plus');
	    $form->addLinkSubmit('cancel', '', 'remove', $this->link('cancel!'));
	    $form->enableBootstrap(['primary' => ['saveRole'], 'default' => ['cancel']], TRUE);
    }

	protected function successFlashMessage() {
		$this->flashMessage('common.alert.newEntrySaved', 'success');
	}

}