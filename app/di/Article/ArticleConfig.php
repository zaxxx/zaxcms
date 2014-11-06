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

	public function getListShowTags() {
		$bc = $this->config['list']['showTags'];
		return $bc === NULL
			? $this->user->isAllowed('WebContent', 'Edit')
			: $bc;
	}

	public function getArticleShowTags() {
		$bc = $this->config['article']['showTags'];
		return $bc === NULL
			? $this->user->isAllowed('WebContent', 'Edit')
			: $bc;
	}

	public function getShowTimePosted() {
		$bc = $this->config['article']['showTimePosted'];
		return $bc === NULL
			? $this->user->isAllowed('WebContent', 'Edit')
			: $bc;
	}

	public function getShowCategory() {
		$bc = $this->config['article']['showCategory'];
		return $bc === NULL
			? $this->user->isAllowed('WebContent', 'Edit')
			: $bc;
	}

	public function getShowAuthor() {
		$bc = $this->config['article']['showAuthor'];
		return $bc === NULL
			? $this->user->isAllowed('WebContent', 'Edit')
			: $bc;
	}

	public function getArticleTagsOnBottom() {
		$bc = $this->config['article']['tagsOnBottom'];
		return $bc === NULL
			? $this->user->isAllowed('WebContent', 'Edit')
			: $bc;
	}

	public function getListTagsOnBottom() {
		$bc = $this->config['list']['tagsOnBottom'];
		return $bc === NULL
			? $this->user->isAllowed('WebContent', 'Edit')
			: $bc;
	}

	public function getCategorySidebarWidth() {
		return $this->config['category']['sidebarWidth'];
	}

	public function getArticleSidebarWidth() {
		return $this->config['article']['sidebarWidth'];
	}

	public function getAuthorSidebarWidth() {
		return $this->config['author']['sidebarWidth'];
	}


	public function getArticleDefaults($key) {
		return $this->config['article']['defaults'][$key];
	}

}

trait TInjectArticleConfig {

	/** @var ArticleConfig */
	protected $articleConfig;

	public function injectArticleConfig(ArticleConfig $articleConfig) {
		$this->articleConfig = $articleConfig;
	}

}