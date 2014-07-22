<?php

namespace Zax\Tests\Latte;
use Tester,
	Tester\Assert,
	Nette,
	Kdyby,
	Zax,
	ZaxCMS;

$container = require __DIR__ . '/../../bootstrap.php';

class HelpersTest extends Tester\TestCase {

	/** @var Nette\DI\Container */
	private $container;

	/** @var Kdyby\Translation\Translator */
	private $translator;

	/** @var Zax\Latte\Helpers */
	private $helpers;

	public function __construct(Nette\DI\Container $container) {
		$this->container = $container;
		$this->translator = $container->getByType('Kdyby\Translation\Translator');
	}

	public function setUp() {
		$this->helpers = new Zax\Latte\Helpers;
		$this->translator->setLocale('en_US');
		$this->helpers->setTranslator($this->translator);
	}

	public function testHelpers() {
		$time = Nette\Utils\DateTime::from('2000-01-02 03:04:05');

		Assert::same('sunday', $this->helpers->beautifulDayOfWeek($time->format('N')));
		Assert::same('january', $this->helpers->beautifulMonth($time->format('n')));
		Assert::same('03:04', $this->helpers->beautifulTime($time));
		Assert::same('sunday 2. january 2000', $this->helpers->beautifulDate($time));
		Assert::same('sunday 2. january 2000 - 03:04', $this->helpers->beautifulDateTime($time));
	}

}

$test = new HelpersTest($container);
$test->run();
