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

class SecurityFormControl extends FormControl {

    protected $selectedUser;

    protected $userService;

    protected $aclFactory;

    protected $roleService;

	protected $loginService;

    public function __construct(Model\CMS\Service\UserService $userService,
                                  Model\CMS\AclFactory $aclFactory,
                                  Model\CMS\Service\RoleService $roleService,
								Model\CMS\Service\UserLoginService $loginService) {
        $this->userService = $userService;
        $this->aclFactory = $aclFactory;
        $this->roleService = $roleService;
	    $this->loginService = $loginService;
    }

	/** @secured Users, Secure */
    public function viewDefault() {}

	/** @secured Users, Secure */
    public function beforeRender() {}

    public function setSelectedUser(Model\CMS\Entity\User $user) {
        $this->selectedUser = $user;
        return $this;
    }

    public function handleCancel() {
        $this->parent->go('this', ['view' => 'Default']);
    }

    public function createForm() {
        $f = parent::createForm();

        if(!$this->selectedUser->role->canBeInheritedFrom()) {
            $f->addStatic('role', 'user.form.role')
                ->addFilter(function($role) {
                    return $role->displayName;
                });
        } else {
            $f->addSelect('userrole', 'user.form.role', $this->roleService->getFormSelectOptions())
                ->setDefaultValue($this->selectedUser->role->id);
        }

	    $f->addCheckbox('banned', 'user.form.banned')
		    ->setDefaultValue($this->selectedUser->login->isBanned);

	    $this->binder->entityToForm($this->selectedUser, $f);

	    $f->addProtection();

        $f->addButtonSubmit('saveSettings', 'common.button.save', 'ok');
        $f->addLinkSubmit('cancel', '', 'remove', $this->link('cancel!'));

        $f->enableBootstrap(['success' => ['saveSettings'], 'default' => ['cancel']], TRUE);

        return $f;
    }

    public function formSuccess(Form $form, $values) {
		if($form->submitted === $form['saveSettings']) {
			if($this->selectedUser->role->canBeInheritedFrom()) {
				if($role = $this->roleService->get($values->userrole)) {
					$this->selectedUser->role = $role;
					$this->userService->persist($this->selectedUser);
				}
			}

			$this->selectedUser->login->isBanned = $values->banned;
			$this->loginService->persist($this->selectedUser->login);

			$this->loginService->flush();
			$this->aclFactory->invalidateCache();

			$this->flashMessage('common.alert.changesSaved', 'success');
			$this->parent->go('this', ['view' => 'Default']);
		}
    }

    public function formError(Form $form) {

    }

}