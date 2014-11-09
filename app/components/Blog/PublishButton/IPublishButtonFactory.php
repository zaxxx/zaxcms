<?php

namespace ZaxCMS\Components\Blog;

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