<?php

namespace ZaxCMS\AdminModule\Components\Mails;

interface ITestMailFormFactory {

    /** @return TestMailFormControl */
    public function create();

}


trait TInjectTestMailFormFactory {

	/** @var ITestMailFormFactory */
	protected $testMailFormFactory;

	public function injectTestMailFormFactory(ITestMailFormFactory $testMailFormFactory) {
		$this->testMailFormFactory = $testMailFormFactory;
	}

}

