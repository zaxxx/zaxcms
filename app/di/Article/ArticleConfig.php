<?php

namespace ZaxCMS\DI;
use Zax,
	Nette,
	ZaxCMS;

class ArticleConfig extends AbstractConfig {

	protected $user;

	public function __construct(array $config, Nette\Security\User $user) {
		parent::__construct($config);
		$this->user = $user;
	}

	public function getListItemsPerPage() {
		return $this->config['list']['itemsPerPage'];
	}

	public function getShowBreadCrumb() {
		$bc = $this->config['category']['showBreadCrumb'];
		return $bc === NULL
			? $this->user->isAllowed('WebContent', 'Edit')
			: $bc;
	}

	public function getShowSubcategories() {
		$sub = $this->config['category']['showSubcategories'];
		return $sub === NULL
			? $this->user->isAllowed('WebContent', 'Edit')
			: $sub;
	}

}

trait TInjectArticleConfig {

	/** @var ArticleConfig */
	protected $articleConfig;

	public function injectArticleConfig(ArticleConfig $articleConfig) {
		$this->articleConfig = $articleConfig;
	}

}