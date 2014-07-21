<?php

namespace Zax\Tests\Application\UI\SnippetGenerators;
use Tester,
	Tester\Assert,
	Nette,
	Zax,
	ZaxCMS;

$container = require __DIR__ . '/../../../../bootstrap.php';

class ShortSnippetGeneratorTest extends Tester\TestCase {

	/** @var Nette\DI\Container */
	private $container;

	/** @var ZaxCMS\TestModule\TestControl */
	private $control;

	/** @var Zax\Application\UI\SnippetGenerators\ShortSnippetGenerator */
	private $snippetGenerator;

	public function __construct(Nette\DI\Container $container) {
		$this->container = $container;
		$pFactory = $container->getByType('Nette\Application\IPresenterFactory');
		$this->control = $pFactory->createPresenter('Test:Test')->getComponent('testComponent');
		$this->snippetGenerator = new Zax\Application\UI\SnippetGenerators\ShortSnippetGenerator;
	}

	public function testSnippetGenerator() {
		Assert::same('testComponent', $this->snippetGenerator->getSnippetId($this->control));
		Assert::same('testComponent-foo', $this->snippetGenerator->getSnippetId($this->control, 'foo'));
	}

}

$test = new ShortSnippetGeneratorTest($container);
$test->run();
