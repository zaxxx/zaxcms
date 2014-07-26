<?php

namespace ZaxCMS\Model;
use Zax,
	ZaxCMS,
	Nette,
	Kdyby,
	Gedmo;


class PrivilegeService extends Service {

	public function __construct(Kdyby\Doctrine\EntityManager $em) {
		$this->em = $em;
		$this->className = Privilege::getClassName();
	}

	public function createPrivilege($name, $displayName, $description = NULL) {
		$priv = new Privilege;
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