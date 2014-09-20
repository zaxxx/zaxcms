<?php

namespace ZaxCMS\Model\CMS\Service;
use Zax,
	ZaxCMS,
	ZaxCMS\Model\CMS\Entity,
	Nette,
	Kdyby;


class WebContentService extends Zax\Model\Doctrine\Service {

	public function __construct(Kdyby\Doctrine\EntityManager $entityManager) {
		parent::__construct($entityManager);
		$this->entityClassName = Entity\WebContent::getClassName();
	}

	public function getLogEntries(WebContent $entity) {
		return $this->getEntityManager()->getRepository('Gedmo\Loggable\Entity\LogEntry')
			->getLogEntries($entity);
	}

	public function createWebContent($name) {
		$webContent = $this->create();
		$webContent->name = $name;
		$this->persist($webContent);
		$this->flush();
		return $webContent;
	}

	public function persist($entity) {
		$entity->lastUpdated = new Nette\Utils\DateTime;
		return parent::persist($entity);
	}

	public function getByName($name) {
		return $this->getBy(['name' => $name]);
	}

}