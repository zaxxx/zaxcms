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

class DeleteRoleFormControl extends FormControl {

	protected $deleteRole;

	protected $role;

	public function __construct(Model\CMS\DeleteRole $deleteRole) {
		$this->deleteRole = $deleteRole;
	}

	public function setRole(Model\CMS\Entity\Role $role) {
		if(!$role->canBeDeleted()) {
			throw new Model\CMS\ProtectedRoleException('This role cannot be removed.');
		}
		$this->role = $role;
		return $this;
	}

    public function viewDefault() {}
    
    public function beforeRender() {}

	public function handleCancel() {
		$this->parent->go('this', ['view' => 'Default', 'selectRole' => NULL]);
	}

	public function createForm() {
		$f = parent::createForm();
		$f->addButtonSubmit('deleteItem', 'common.button.delete', 'trash');
		$f->addLinkSubmit('cancel', '', 'remove', $this->link('cancel!'));
		$f->enableBootstrap(['danger' => ['deleteItem'], 'default' => ['cancel']], TRUE, 3, 'sm', 'form-inline');
		return $f;
	}
    
    public function formSuccess(Form $form, $values) {
	    if($form->submitted === $form['deleteItem']) {
		    $this->deleteRole->deleteRole($this->role);
		    $this->parent->onUpdate();
		    $this->flashMessage('common.alert.entryDeleted', 'success');
		    $this->parent->go('this', ['view' => 'Default', 'selectRole' => NULL]);
	    }
    }
    
    public function formError(Form $form) {
        
    }

}