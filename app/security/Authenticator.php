<?php

namespace ZaxCMS\Security;
use Zax,
	ZaxCMS,
	ZaxCMS\Model,
	Nette;

class Authenticator extends Nette\Object implements Nette\Security\IAuthenticator {

	protected $loginFacade;

	public function __construct(Model\LoginFacade $loginFacade) {
		$this->loginFacade = $loginFacade;
	}

	public function authenticate(array $credentials) {
		$user = $this->loginFacade->checkCredentials($credentials);
		$roles = [
			$user->role->name
		];
		$data = [
			'email' => $user->email,
			'name' => $user->name
		];
		return new Nette\Security\Identity($user->id, $roles, $data);
	}

}