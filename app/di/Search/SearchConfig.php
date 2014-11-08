<?php

namespace ZaxCMS\DI;
use Zax,
	Nette,
	ZaxCMS;

class SearchConfig extends Zax\DI\ExtensionConfig {

	public function getArticlesPerPage() {
		return $this->config['articlesPerPage'];
	}

	public function getArticlesEnabled() {
		return $this->config['articlesEnabled'] === TRUE;
	}

}

trait TInjectSearchConfig {

	/** @var SearchConfig */
	protected $searchConfig;

	public function injectArticleConfig(SearchConfig $searchConfig) {
		$this->searchConfig = $searchConfig;
	}

}