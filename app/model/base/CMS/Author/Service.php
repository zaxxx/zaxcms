<?php

namespace ZaxCMS\Model\CMS\Service;
use Zax,
	ZaxCMS,
	ZaxCMS\Model\CMS\Entity,
	Nette,
	Kdyby,
	Gedmo;


class AuthorService extends Zax\Model\Doctrine\Service {

	public function __construct(Kdyby\Doctrine\EntityManager $entityManager) {
		parent::__construct($entityManager);
		$this->entityClassName = Entity\Author::getClassName();
	}

	public function getOrCreateAuthor($name) {
		$author = $this->getBy(['name' => $name]);
		if($author === NULL) {
			$author = $this->create();
			$author->name = $name;
			$author->aboutAuthor = '#### ' . $name;
			$this->persist($author);
			$this->flush();
		}
		return $author;
	}

}


trait TInjectAuthorService {

	/** @var AuthorService */
	protected $authorService;

	public function injectAuthorService(AuthorService $authorService) {
		$this->authorService = $authorService;
	}

}

