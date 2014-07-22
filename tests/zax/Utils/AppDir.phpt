<?php

namespace Zax\Tests\Utils;
use Tester,
	Tester\Assert,
	Nette,
	Zax,
	ZaxCMS;

$container = require __DIR__ . '/../../bootstrap.php';

class AppDirTest extends Tester\TestCase {

	/** @var Nette\DI\Container */
	private $container;

	/** @var Zax\Utils\AppDir */
	private $appDir;

	public function __construct(Nette\DI\Container $container) {
		$this->container = $container;
		$this->appDir = $container->getByType('Zax\Utils\AppDir');
	}

	public function testAppDir() {
		Assert::same(realpath(__DIR__ . '/../../../app'), (string)$this->appDir);
	}

}

$test = new AppDirTest($container);
$test->run();
