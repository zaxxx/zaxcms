<?php

namespace ZaxCMS\Model\CMS\Service;
use Zax,
	ZaxCMS,
	ZaxCMS\Model\CMS\Entity,
	Nette,
	Kdyby,
	Gedmo;


class ArticleService extends Zax\Model\Doctrine\Service {

	public function __construct(Kdyby\Doctrine\EntityManager $entityManager) {
		parent::__construct($entityManager);
		$this->entityClassName = Entity\Article::getClassName();
	}

}


trait TInjectArticleService {

	/** @var ArticleService */
	protected $articleService;

	public function injectArticleService(ArticleService $articleService) {
		$this->articleService = $articleService;
	}

}

