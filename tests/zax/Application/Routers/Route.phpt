<?php

namespace Zax\Tests\Application\Routers;
use Tester,
	Tester\Assert,
	Nette,
	Zax,
	ZaxCMS;

$container = require __DIR__ . '/../../../bootstrap.php';

class RouteTest extends Tester\TestCase {

	/** @var Nette\DI\Container */
	private $container;

	public function __construct(Nette\DI\Container $container) {
		$this->container = $container;
	}

	public function testAliases() {
		$meta = Zax\Application\Routers\Route::meta('Test', 'test',
			[
				'tc' => 'testComponent-stringParam',
				'yes' => 'testComponent-boolParam'
			],
			['yes']
		);
		$route = new Zax\Application\Routers\Route('<presenter>/<action>[/<tc>[/<yes>]]', $meta);

		$url1 = new Nette\Http\UrlScript('http://example.com/test.test/default/hello');
		$netteReq1 = $route->match(new Nette\Http\Request($url1));
		Assert::same('hello', $netteReq1->getParameters()['testComponent-stringParam']);

		$url2 = new Nette\Http\UrlScript('http://example.com/test.test/default/hello/yes');
		$netteReq2 = $route->match(new Nette\Http\Request($url2));

		Assert::same('hello', $netteReq2->getParameters()['testComponent-stringParam']);
		Assert::same(TRUE, $netteReq2->getParameters()['testComponent-boolParam']);
	}

}

$test = new RouteTest($container);
$test->run();
