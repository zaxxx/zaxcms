<?php

namespace ZaxCMS\Model;
use Zax,
	ZaxCMS,
	Nette,
	Kdyby;


class WebContentService extends Service {

	public function __construct(Kdyby\Doctrine\EntityManager $em) {
		$this->em = $em;
		$this->className = WebContent::getClassName();
	}

	public function getLogEntries(WebContent $entity) {
		return $this->em->getRepository('Gedmo\Loggable\Entity\LogEntry')
			->getLogEntries($entity);
	}

	public function createWebContent($name) {
		$webContent = new WebContent;
		$webContent->name = $name;
		$this->em->persist($webContent);
		$this->em->flush();
		return $webContent;
	}

}