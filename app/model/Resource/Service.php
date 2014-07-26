<?php

namespace ZaxCMS\Model;
use Zax,
	ZaxCMS,
	Nette,
	Kdyby,
	Gedmo;


class ResourceService extends Service {

	public function __construct(Kdyby\Doctrine\EntityManager $em) {
		$this->em = $em;
		$this->className = Resource::getClassName();
	}

	public function createResource($name, $displayName, $description = NULL) {
		$res = new Resource;
		$res->name = $name;
		$res->displayName = $displayName;
		$res->description = $description;

		$this->persist($res);
		return $res;
	}

	public function createDefaultResources() {
		$this->createResource('WebContent', 'Web content');
		$this->createResource('Menu', 'Menu');
		$this->createResource('FileManager', 'File manager');
		$this->createResource('Pages', 'Pages');

		$this->flush();
	}

}