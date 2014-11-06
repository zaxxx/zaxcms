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
		$articleConfig = $this->articleConfig;
		$article->isMain = $articleConfig->getArticleDefaults('isMain');
		$article->isVisibleInRootCategory = $articleConfig->getArticleDefaults('isVisibleInRootCategory');
		$article->isPublic = $articleConfig->getArticledefaults('isPublic');
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

