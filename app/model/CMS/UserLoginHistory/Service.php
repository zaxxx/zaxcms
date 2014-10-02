<?php

namespace ZaxCMS\Model\CMS\Service;
use Zax,
	ZaxCMS,
	ZaxCMS\Model\CMS\Entity,
	Nette,
	Kdyby,
	Gedmo;


class UserLoginHistoryService extends Zax\Model\Doctrine\Service {

	public function __construct(Kdyby\Doctrine\EntityManager $entityManager) {
		parent::__construct($entityManager);
		$this->entityClassName = Entity\UserLoginHistory::getClassName();
	}

	public function logLogin(Entity\User $user) {
		$logEntry = $this->create();
		$logEntry->user = $user;
		$logEntry->timeAt = new Nette\Utils\DateTime;
		$this->persist($logEntry);
		$this->flush();
	}

}