<?php

namespace ZaxCMS\Components\Blog;

interface IDeleteTagFormFactory {

    /** @return DeleteTagFormControl */
    public function create();

}

trait TInjectDeleteTagFormFactory {

	/** @var IDeleteTagFormFactory */
	protected $deleteTagFormFactory;

	public function injectDeleteTagFormFactory(IDeleteTagFormFactory $factory) {
		$this->deleteTagFormFactory = $factory;
	}

}