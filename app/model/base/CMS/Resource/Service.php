<?php

namespace ZaxCMS\Model\CMS\Service;
use Zax,
	ZaxCMS,
	ZaxCMS\Model\CMS\Entity,
	Nette,
	Kdyby,
	Gedmo;


class ResourceService extends Zax\Model\Doctrine\Service {

	protected $defaultResources = [
		'AdminPanel' => ['cs_CZ' => 'Administrace', 'en_US' => 'Admin panel'],
		'WebContent' => ['cs_CZ' => 'Obsah webu', 'en_US' => 'Web content'],
		'Menu' => ['cs_CZ' => 'Navigace', 'en_US' => 'Navigation'],
		'FileManager' => ['cs_CZ' => 'Správce souborů', 'en_US' => 'File manager'],
		'Pages' => ['cs_CZ' => 'Stránky', 'en_US' => 'Pages'],
		'Users' => ['cs_CZ' => 'Uživatelé', 'en_US' => 'Users'],
		'Roles' => ['cs_CZ' => 'Role', 'en_US' => 'Roles']
	];

	public function __construct(Kdyby\Doctrine\EntityManager $entityManager) {
		parent::__construct($entityManager);
		$this->entityClassName = Entity\Resource::getClassName();
	}

	public function createResource($name) {
		$res = $this->create();
		$res->name = $name;

		return $res;
	}

	public function createDefaultResources() {
		foreach($this->defaultResources as $resource => $translations) {
			$entity = $this->createResource($resource);
			$entity->setTranslatableLocale('cs_CZ');
			$entity->note = $translations['cs_CZ'];
			$this->persist($entity);
		}
		$this->flush();

		foreach($this->findAll() as $res) {
			$res->setTranslatableLocale('en_US');
			$res->note = $this->defaultResources[$res->name]['en_US'];
			$this->persist($res);
		}
		$this->flush();
	}

}


trait TInjectResourceService {

	/** @var ResourceService */
	protected $resourceService;

	public function injectResourceService(ResourceService $resourceService) {
		$this->resourceService = $resourceService;
	}

}

