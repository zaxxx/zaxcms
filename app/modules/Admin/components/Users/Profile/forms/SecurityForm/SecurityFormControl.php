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

	/** @secured Users, Use */
    public function viewDefault() {
	    if(!$this->user->isAllowed('Users', 'Ban') && !$this->user->isAllowed('Users', 'Secure')) {
		    throw new Zax\Security\ForbiddenRequestException;
	    }
    }

	/** @secured Users, Use */
    public function beforeRender() {
	    if(!$this->user->isAllowed('Users', 'Ban') && !$this->user->isAllowed('Users', 'Secure')) {
		    throw new Zax\Security\ForbiddenRequestException;
	    }
    }

    public function setSelectedUser(Model\CMS\Entity\User $user) {
	    if($user->role->isAdminRole()) {
		    throw new Model\CMS\ProtectedRoleException;
	    }
        $this->selectedUser = $user;
        return $this;
    }

    public function handleCancel() {
        $this->parent->go('this', ['view' => 'Default']);
    }

    public function createForm() {
        $f = parent::createForm();

	    if($this->user->isAllowed('Users', 'Secure')) {
	        $f->addSelect('userrole', 'user.form.role', $this->roleService->getFormSelectOptions())
	            ->setDefaultValue($this->selectedUser->role->id)
	            ->addCondition($f::EQUAL, $this->roleService->getAdminRole()->id)
	                ->toggle('superadminWarning');

		    $f->addStatic('superadminWarning', '')
			    ->setValue($this->translator->translate('user.form.superadminWarning'))
			    ->setOption('id', 'superadminWarning')
			    ->getControlPrototype()
			    ->addClass('has-error');
	    }


	    if($this->user->isAllowed('Users', 'Ban')) {
		    $f->addCheckbox('banned', 'user.form.banned')
			    ->setDefaultValue($this->selectedUser->login->isBanned);
	    }

	    $this->binder->entityToForm($this->selectedUser, $f);

	    $f->addProtection();

        $f->addButtonSubmit('saveSettings', 'common.button.save', 'ok');
        $f->addLinkSubmit('cancel', '', 'remove', $this->link('cancel!'));

        $f->enableBootstrap(['success' => ['saveSettings'], 'default' => ['cancel']], TRUE);

        return $f;
    }

    public function formSuccess(Form $form, $values) {
		if($form->submitted === $form['saveSettings']) {
			if($this->user->isAllowed('Users', 'Secure') && $role = $this->roleService->get($values->userrole)) {
				$this->selectedUser->role = $role;
				$this->userService->persist($this->selectedUser);
			}

			if($this->user->isAllowed('Users', 'Ban')) {
				$this->selectedUser->login->isBanned = $values->banned;
				$this->loginService->persist($this->selectedUser->login);
			}

			$this->loginService->flush();
			$this->aclFactory->invalidateCache();

			$this->flashMessage('common.alert.changesSaved', 'success');
			$this->parent->go('this', ['view' => 'Default']);
		}
    }

    public function formError(Form $form) {

    }

}