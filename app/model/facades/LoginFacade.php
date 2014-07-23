<?php

namespace ZaxCMS\Model;
use Zax,
	ZaxCMS,
	Nette;

class LoginFacade extends Nette\Object {

	const   LOGIN_DISABLED = 0,
			LOGIN_BY_NAME = 1,
			LOGIN_BY_EMAIL = 2;

	protected $userService;

	protected $userLoginService;

	protected $loginType;

	/**
	 * @param int              $loginType
	 * @param UserService      $userService
	 * @param UserLoginService $userLoginService
	 */
	public function __construct($loginType = self::LOGIN_BY_NAME, UserService $userService, UserLoginService $userLoginService) {
		$this->loginType = $loginType;
		$this->userLoginService = $userLoginService;
		$this->userService = $userService;
	}

	/**
	 * @param User $user
	 * @param      $password
	 * @return User
	 * @throws \ZaxCMS\Security\UnverifiedUserException
	 * @throws \ZaxCMS\Security\InvalidPasswordException
	 * @throws \ZaxCMS\Security\BannedUserException
	 */
	public function checkCredentialsByUser(User $user, $password) {
		$login = $user->login;
		/** @var UserLogin $login */
		if(!Nette\Security\Passwords::verify($password, $login->password)) {
			throw new ZaxCMS\Security\InvalidPasswordException;
		}
		if($login->isBanned) {
			throw new ZaxCMS\Security\BannedUserException;
		}
		if(!$login->isVerified()) {
			throw new ZaxCMS\Security\UnverifiedUserException;
		}
		return $user;
	}

	/**
	 * @param $name
	 * @param $password
	 * @return User
	 * @throws \ZaxCMS\Security\InvalidNameException
	 * @throws \ZaxCMS\Security\UnverifiedUserException
	 * @throws \ZaxCMS\Security\InvalidPasswordException
	 * @throws \ZaxCMS\Security\BannedUserException
	 */
	public function checkCredentialsByName($name, $password) {
		$user = $this->userService->getBy(['name' => $name]);
		if($user === NULL) {
			throw new ZaxCMS\Security\InvalidNameException;
		}
		return $this->checkCredentialsByUser($user, $password);
	}

	/**
	 * @param $email
	 * @param $password
	 * @return User
	 * @throws \ZaxCMS\Security\InvalidEmailException
	 * @throws \ZaxCMS\Security\UnverifiedUserException
	 * @throws \ZaxCMS\Security\InvalidPasswordException
	 * @throws \ZaxCMS\Security\BannedUserException
	 */
	public function checkCredentialsByEmail($email, $password) {
		$user = $this->userService->getBy(['email' => $email]);
		if($user === NULL) {
			throw new ZaxCMS\Security\InvalidEmailException;
		}
		return $this->checkCredentialsByUser($user, $password);
	}

	/**
	 * @param array $credentials
	 * @return User
	 * @throws \ZaxCMS\Security\UserLoginDisabledException
	 * @throws \ZaxCMS\Security\UnverifiedUserException
	 * @throws \ZaxCMS\Security\InvalidPasswordException
	 * @throws \ZaxCMS\Security\BannedUserException
	 * @throws \ZaxCMS\Security\InvalidEmailException
	 * @throws \ZaxCMS\Security\InvalidNameException
	 */
	public function checkCredentials(array $credentials) {
		list($login, $password) = $credentials;
		if($this->loginType === self::LOGIN_BY_NAME) {
			return $this->checkCredentialsByName($login, $password);
		} else if ($this->loginType === self::LOGIN_BY_EMAIL) {
			return $this->checkCredentialsByEmail($login, $password);
		} else {
			throw new ZaxCMS\Security\UserLoginDisabledException;
		}
	}

	/**
	 * @param string $email
	 * @param string $name
	 * @param string $password
	 * @param Role $role
	 * @return User
	 */
	public function createUser($email, $name, $password, Role $role) {
		$user = new User;
		$user->email = $email;
		$user->name = $name;
		$user->role = $role;
		$user->login = new UserLogin;
		$user->login->password = Nette\Security\Passwords::hash($password);
		$user->login->registeredAt = new Nette\Utils\DateTime;
		$user->login->isBanned = FALSE;
		$user->login->verifyHash = Nette\Utils\Random::generate(15);
		$this->userService->persist($user);
		$this->userLoginService->persist($user->login);
		$this->userService->flush();
		return $user;
	}

}