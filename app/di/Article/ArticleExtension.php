<?php

namespace ZaxCMS\DI;
use Zax,
	Nette,
	ZaxCMS;

class ArticleExtension extends Nette\DI\CompilerExtension {

	protected $defaults =  [
		'list' => [
			'itemsPerPage' => 10
		],
		'category' => [
			'showBreadCrumb' => TRUE,
			'showSubcategories' => TRUE
		]
	];

	public function loadConfiguration() {
		$config = $this->getConfig($this->defaults);

		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('articleConfig'))
			->setClass('ZaxCMS\DI\ArticleConfig', [$config]);
	}

}