<?php

namespace ZaxCMS\Model\CMS;
use Zax,
	ZaxCMS,
	Nette;

class ChangePassword extends Nette\Object {

	protected $auth;

	protected $user;

	protected $userService;

	protected $userLoginService;

	protected $aclFactory;

	public function __construct(Auth $auth,
	                            Nette\Security\User $user,
	                            Service\UserService $userService,
	                            Service\UserLoginService $userLoginService,
								AclFactory $aclFactory) {
		$this->auth = $auth;
		$this->user = $user;
		$this->userService = $userService;
		$this->userLoginService = $userLoginService;
		$this->aclFactory = $aclFactory;
	}

	public function changePassword($oldPassword, $newPassword) {
		$this->auth->checkCredentials([$this->user->identity->name, $oldPassword]);

		$user = $this->userService->get((int)$this->user->identity->id);
		$login = $user->login;
		$login->password = Nette\Security\Passwords::hash($newPassword);
		$login->passwordLastChangedAt = new Nette\Utils\DateTime;
		$this->userLoginService->persist($login);
		$this->userLoginService->flush();
		$this->aclFactory->invalidateCache();
	}

}


trait TInjectChangePassword {

	/** @var ChangePassword */
	protected $changePassword;

	public function injectChangePassword(ChangePassword $changePassword) {
		$this->changePassword = $changePassword;
	}

}

