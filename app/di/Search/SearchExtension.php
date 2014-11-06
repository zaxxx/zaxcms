<?php

namespace ZaxCMS\DI;
use Zax,
	Nette,
	ZaxCMS;

class SearchExtension extends Nette\DI\CompilerExtension {

	protected $defaults =  [
		'articles' => TRUE,
		'categories' => TRUE,
		'tags' => TRUE
	];

	public function loadConfiguration() {
		$config = $this->getConfig($this->defaults);

		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('searchConfig'))
			->setClass('ZaxCMS\DI\SearchConfig', [$config]);

	}

}