<?php

namespace ZaxCMS\Components\Auth;
use Zax,
	ZaxCMS,
	ZaxCMS\Model,
	Nette,
	Kdyby;

class LoginLogListener extends Nette\Object implements Kdyby\Events\Subscriber {

	protected $loginHistoryService;

	protected $userService;

	public function __construct(Model\CMS\Service\UserLoginHistoryService $loginHistoryService,
								Model\CMS\Service\UserService $userService) {
		$this->loginHistoryService = $loginHistoryService;
		$this->userService = $userService;
	}

	public function getSubscribedEvents() {
		return ['ZaxCMS\Components\Auth\LoginFormControl::onLogin'];
	}

	public function onLogin($userId) {
		$this->loginHistoryService->logLogin($this->userService->get($userId));
	}

}