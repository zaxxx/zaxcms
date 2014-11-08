<?php

namespace ZaxCMS\Components\Article;

interface IPublishButtonFactory {

    /** @return PublishButtonControl */
    public function create();

}

trait TInjectPublishButtonFactory {

	/** @var IPublishButtonFactory */
	protected $publishButtonFactory;

	public function injectPublishButtonFactory(IPublishButtonFactory $factory) {
		$this->publishButtonFactory = $factory;
	}

}