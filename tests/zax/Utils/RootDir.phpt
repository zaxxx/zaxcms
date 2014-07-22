<?php

namespace Zax\Tests\Utils;
use Tester,
	Tester\Assert,
	Nette,
	Zax,
	ZaxCMS;

$container = require __DIR__ . '/../../bootstrap.php';

class RootDirTest extends Tester\TestCase {

	/** @var Nette\DI\Container */
	private $container;

	/** @var Zax\Utils\RootDir */
	private $rootDir;

	public function __construct(Nette\DI\Container $container) {
		$this->container = $container;
		$this->rootDir = $container->getByType('Zax\Utils\RootDir');
	}
	public function testRootDir() {
		Assert::same(realpath(__DIR__ . '/../../..'), (string)$this->rootDir);
	}

}

$test = new RootDirTest($container);
$test->run();
