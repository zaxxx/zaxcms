<?php

namespace ZaxCMS\InstallModule\Components\Install;
use Nette,
    Zax,
	ZaxCMS,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\FormControl;

class CreateUserControl extends FormControl {

	protected $loginFacade;

	protected $roleService;

	public function __construct(Model\CMS\Auth $loginFacade, Model\CMS\Service\RoleService $roleService) {
		$this->loginFacade = $loginFacade;
		$this->roleService = $roleService;
	}

    public function viewDefault() {}
    
    public function beforeRender() {}

	public function formSuccess(Nette\Forms\Form $form, $values) {
		$adminRole = $this->roleService->getAdminRole();
		$user = $this->loginFacade->createUser($values->email, $values->name, $values->password, $adminRole, TRUE);
		$loginType = $this->loginFacade->getLoginType();
		try {
			$this->user->login(($loginType === Model\CMS\Auth::LOGIN_BY_NAME ? $user->name : $user->email), $values->password);
			$this->parent->installed();
		} catch (Nette\Security\AuthenticationException $ex) {
			$this->flashMessage($ex->getMessage(), 'danger');
		}
	}

	public function formError(Nette\Forms\Form $form) {
		$this->flashMessage('error', 'danger');
	}

	public function createForm() {
		$f = parent::createForm();

		$f->addText('name', 'system.form.username')
			->setRequired();
		$f->addText('email', 'system.form.email')
			->setRequired()
			->addRule($f::EMAIL);
		$f->addPassword('password', 'system.form.password')
			->setRequired();
		$f->addPassword('password2', 'system.form.passwordRepeat')
			->setRequired()
			->addRule($f::EQUAL, 'system.form.passwordNotMatch', $f['password']);

		$f->addButtonSubmit('createUser', 'system.button.createUser', 'user');

		$f->enableBootstrap(['success' => ['createUser']]);

		return $f;
	}

}