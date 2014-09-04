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

	public function createPrivilege($name, $displayName, $description = NULL) {
		$priv = $this->create();
		$priv->name = $name;
		$priv->displayName = $displayName;
		$priv->description = $description;

		$this->persist($priv);
		return $priv;
	}

	public function createDefaultPrivileges() {
		$this->createPrivilege('Show', 'show');
		$this->createPrivilege('Add', 'add');
		$this->createPrivilege('Edit', 'edit');
		$this->createPrivilege('Delete', 'delete');
		$this->createPrivilege('Upload', 'upload');
		$this->createPrivilege('Publish', 'publish');

		$this->flush();
	}

}