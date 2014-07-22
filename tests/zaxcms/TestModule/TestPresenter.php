<?php

namespace ZaxCMS\TestModule;
use Zax,
	ZaxCMS;

class TestPresenter extends Zax\Application\UI\Presenter {

	protected $testComponentFactory;

	public function __construct(ITestComponentFactory $testComponentFactory) {
		$this->testComponentFactory = $testComponentFactory;
	}

	public function actionDefault() {

	}

	protected function createComponentTestComponent() {
	    return $this->testComponentFactory->create();
	}

}