<?php

namespace ZaxCMS\Components\Search;

interface ISearchFactory {

    /** @return SearchControl */
    public function create();

}

trait TInjectSearchFactory {

	/** @var ISearchFactory */
	protected $searchFactory;

	public function injectSearchFactory(ISearchFactory $factory) {
		$this->searchFactory = $factory;
	}

}