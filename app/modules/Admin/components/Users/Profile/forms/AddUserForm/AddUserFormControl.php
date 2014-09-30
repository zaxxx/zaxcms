<?php

namespace ZaxCMS\AdminModule\Components\Users;
use Nette,
    Zax,
    ZaxCMS\Model,
    Nette\Forms\Form,
    Zax\Application\UI as ZaxUI,
    Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control,
    Zax\Application\UI\FormControl;

class AddUserFormControl extends UserFormControl {

    protected $auth;

    protected $roleService;

	/** @secured Users, Add */
	public function viewDefault() {}

	/** @secured Users, Add */
	public function beforeRender() {}

    public function __construct(Model\CMS\Auth $auth, Model\CMS\Service\RoleService $roleService) {
        $this->auth = $auth;
        $this->roleService = $roleService;
    }

    public function extendForm(Form $form) {
        $form->addPassword('password', 'system.form.password')
            ->setRequired();
        $form->addPassword('password2', 'system.form.passwordRepeat')
            ->setRequired()
            ->addRule($form::EQUAL, 'system.form.passwordNotMatch', $form['password']);
    }

    public function formSuccess(Form $form, $values) {
        if($form->submitted === $form['saveUser']) {
            $this->auth->createUser($values->email, $values->name, $values->password, $this->roleService->getUserRole());
            $this->aclFactory->invalidateCache();

            $this->flashMessage('common.alert.newEntrySaved', 'success');
            $this->parent->go('this', ['view' => 'Default']);
        }
    }

    public function formError(Form $form) {}

}