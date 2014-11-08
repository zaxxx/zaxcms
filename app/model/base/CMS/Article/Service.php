<?php

namespace ZaxCMS\Model\CMS\Service;
use Zax,
	ZaxCMS,
	ZaxCMS\Model\CMS\Entity,
	Nette,
	Kdyby,
	Gedmo;


class ArticleService extends Zax\Model\Doctrine\Service {

	protected $articleConfig;

	public function __construct(Kdyby\Doctrine\EntityManager $entityManager, ZaxCMS\DI\ArticleConfig $articleConfig) {
		parent::__construct($entityManager);
		$this->entityClassName = Entity\Article::getClassName();
		$this->articleConfig = $articleConfig;
	}

	public function create() {
		$article = parent::create();
		$articleConfig = $this->articleConfig->getConfig();
		$article->isMain = $articleConfig['article']['defaults']['isMain'];
		$article->isVisibleInRootCategory = $articleConfig['article']['defaults']['isVisibleInRootCategory'];
		$article->isPublic = $articleConfig['article']['defaults']['isPublic'];
		return $article;
	}

}


trait TInjectArticleService {

	/** @var ArticleService */
	protected $articleService;

	public function injectArticleService(ArticleService $articleService) {
		$this->articleService = $articleService;
	}

}

