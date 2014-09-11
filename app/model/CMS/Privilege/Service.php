<?php

namespace ZaxCMS\Model\CMS\Service;
use Zax,
	ZaxCMS,
	ZaxCMS\Model\CMS\Entity,
	Nette,
	Kdyby,
	Gedmo;


class PrivilegeService extends Zax\Model\Service {

	public function __construct(Kdyby\Doctrine\EntityManager $entityManager) {
		parent::__construct($entityManager);
		$this->entityClassName = Entity\Privilege::getClassName();
	}

	public function createPrivilege($name) {
		$priv = $this->create();
		$priv->name = $name;

		$this->persist($priv);
		return $priv;
	}

	public function createDefaultPrivileges() {
		$this->createPrivilege('Use');
		$this->createPrivilege('Add');
		$this->createPrivilege('Edit');
		$this->createPrivilege('Delete');
		$this->createPrivilege('Upload');
		$this->createPrivilege('Publish');

		$this->flush();
	}

}