<?php

namespace ZaxCMS\Model\CMS\Service;
use Zax,
	ZaxCMS,
	ZaxCMS\Model\CMS\Entity,
	Nette,
	Kdyby;


class UserService extends Zax\Model\Doctrine\Service {

	public function __construct(Kdyby\Doctrine\EntityManager $entityManager) {
		parent::__construct($entityManager);
		$this->entityClassName = Entity\User::getClassName();
	}

}


trait TInjectUserService {

	/** @var UserService */
	protected $userService;

	public function injectUserService(UserService $userService) {
		$this->userService = $userService;
	}

}

