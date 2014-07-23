<?php

namespace ZaxCMS\Model;
use Zax,
	ZaxCMS,
	Nette,
	Kdyby,
	Gedmo;


class PageService extends Service {

	public function __construct(Kdyby\Doctrine\EntityManager $em) {
		$this->em = $em;
		$this->className = Page::getClassName();
	}

	public function getByName($name) {
		return $this->getBy(['name' => $name]);
	}

} 