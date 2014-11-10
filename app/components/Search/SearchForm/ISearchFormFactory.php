<?php

namespace ZaxCMS\Components\Search;

interface ISearchFormFactory {

    /** @return SearchFormControl */
    public function create();

}

trait TInjectSearchFormFactory {

	/** @var ISearchFormFactory */
	protected $searchFormFactory;

	public function injectSearchFormFactory(ISearchFormFactory $factory) {
		$this->searchFormFactory = $factory;
	}

}