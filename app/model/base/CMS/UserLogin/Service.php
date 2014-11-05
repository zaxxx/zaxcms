<?php

namespace ZaxCMS\Model\CMS\Service;
use Zax,
	ZaxCMS,
	ZaxCMS\Model\CMS\Entity,
	Nette,
	Kdyby;


class UserLoginService extends Zax\Model\Doctrine\Service {

	public function __construct(Kdyby\Doctrine\EntityManager $entityManager) {
		parent::__construct($entityManager);
		$this->entityClassName = Entity\UserLogin::getClassName();
	}

}


trait TInjectUserLoginService {

	/** @var UserLoginService */
	protected $userLoginService;

	public function injectUserLoginService(UserLoginService $userLoginService) {
		$this->userLoginService = $userLoginService;
	}

}

