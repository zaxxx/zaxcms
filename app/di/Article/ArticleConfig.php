<?php

namespace ZaxCMS\DI;
use Zax,
	Nette,
	ZaxCMS;

class ArticleConfig extends Zax\DI\ExtensionConfig {

	public function __construct(array $config, Nette\Security\User $user) {
		parent::__construct($config);

		$this->processPattern(function($value, $key) {
			return $value === NULL;
		}, function($value, $key) use ($user) {
			return $user->isAllowed('WebContent', 'Edit');
		});

	}

	public function getListItemsPerPage() {
		return $this->config['list']['itemsPerPage'];
	}

}

trait TInjectArticleConfig {

	/** @var ArticleConfig */
	protected $articleConfig;

	public function injectArticleConfig(ArticleConfig $articleConfig) {
		$this->articleConfig = $articleConfig;
	}

}