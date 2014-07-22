<?php

namespace Zax\Tests\Application\UI;
use Tester,
	Tester\Assert,
	Nette,
	Zax,
	ZaxCMS;

$container = require __DIR__ . '/../../../bootstrap.php';

class FormTest extends Tester\TestCase {

	/** @var Nette\DI\Container */
	private $container;

	private $formFactory;

	/** @var Zax\Application\UI\Form */
	private $form;

	public function __construct(Nette\DI\Container $container) {
		$this->container = $container;
		$this->formFactory = $container->getByType('Zax\Application\UI\FormFactory');
	}

	public function setUp() {
		$this->form = $this->formFactory->create();
	}

	public function testForm() {
		Assert::type('Zax\Application\UI\Form', $this->form);
	}

	// Not sure what/how to test here.. the Forms code is straightforward anyways

}

$test = new FormTest($container);
$test->run();
