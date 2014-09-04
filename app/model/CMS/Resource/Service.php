<?php

namespace ZaxCMS\Model\CMS\Service;
use Zax,
	ZaxCMS,
	ZaxCMS\Model\CMS\Entity,
	Nette,
	Kdyby,
	Gedmo;


class ResourceService extends Zax\Model\Service {

	public function __construct(Kdyby\Doctrine\EntityManager $entityManager) {
		parent::__construct($entityManager);
		$this->entityClassName = Entity\Resource::getClassName();
	}

	public function createResource($name, $displayName, $description = NULL) {
		$res = $this->create();
		$res->name = $name;
		$res->displayName = $displayName;
		$res->description = $description;

		$this->persist($res);
		return $res;
	}

	public function createDefaultResources() {
		$this->createResource('AdminPanel', 'Admin panel');
		$this->createResource('WebContent', 'Web content');
		$this->createResource('Menu', 'Menu');
		$this->createResource('FileManager', 'File manager');
		$this->createResource('Pages', 'Pages');
		$this->createResource('Users', 'Users');


		$this->flush();
	}

}