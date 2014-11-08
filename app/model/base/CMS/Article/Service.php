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
		$config = $this->articleConfig->getConfig()['article']['defaults'];
		$article->isMain = $config['isMain'];
		$article->isVisibleInRootCategory = $config['isVisibleInRootCategory'];
		$article->isPublic = $config['isPublic'];
		$article->sidebarCategory = $config['sidebarCategory'];
		$article->imageConfig = $config['imageConfig'];
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

