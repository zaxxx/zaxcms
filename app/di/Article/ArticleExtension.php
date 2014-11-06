<?php

namespace ZaxCMS\DI;
use Zax,
	Nette,
	ZaxCMS;

class ArticleExtension extends Nette\DI\CompilerExtension {

	protected $defaults =  [
		'list' => [
			'itemsPerPage' => 10,
			'showTags' => TRUE,
			'tagsOnBottom' => TRUE
		],
		'category' => [
			'showBreadCrumb' => TRUE,
			'showSubcategories' => TRUE,
			'sidebarWidth' => 3
		],
		'article' => [
			'defaults' => [
				'isVisibleInRootCategory' => TRUE,
				'isMain' => FALSE,
				'isPublic' => FALSE
			],
			'showTimePosted' => TRUE,
			'showAuthor' => TRUE,
			'showCategory' => TRUE,
			'showTagsList' => TRUE,
			'showTagsDetail' => TRUE,
			'tagsOnBottom' => FALSE,
			'sidebarWidth' => 3
		],
		'author' => [
			'sidebarWidth' => 3
		]
	];

	public function loadConfiguration() {
		$config = $this->getConfig($this->defaults);

		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('articleConfig'))
			->setClass('ZaxCMS\DI\ArticleConfig', [$config]);
	}

}