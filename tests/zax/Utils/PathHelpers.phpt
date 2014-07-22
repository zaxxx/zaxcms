<?php

namespace Zax\Tests\Utils;
use Tester,
	Tester\Assert,
	Nette,
	Zax,
	Zax\Utils\PathHelpers,
	ZaxCMS;

$container = require __DIR__ . '/../../bootstrap.php';

class PathHelpersTest extends Tester\TestCase {

	/** @var Nette\DI\Container */
	private $container;

	private $rootDir;

	private $testsDir;

	public function __construct(Nette\DI\Container $container) {
		$this->container = $container;
		$this->rootDir = $container->getByType('Zax\Utils\RootDir');
		$this->testsDir = realpath($this->rootDir . '/tests');
	}

	public function testGetDepth() {
		// test without realpath()
		Assert::same(0, PathHelpers::getDepth('foo', TRUE));
		Assert::same(1, PathHelpers::getDepth('/foo', TRUE));
		Assert::same(7, PathHelpers::getDepth('/abc/def/ghi/asfas\asdfas\asdfs/qsdfasdf.abc', TRUE));

		// test with real paths
		$testsDirDepth = PathHelpers::getDepth($this->testsDir);
		Assert::same($testsDirDepth, PathHelpers::getDepth(__DIR__ . '/../..'));
		Assert::same($testsDirDepth+2, PathHelpers::getDepth(__DIR__ . '/../../zax/Utils'));
		Assert::same($testsDirDepth+2, PathHelpers::getDepth($this->testsDir . '/zax/Utils'));
	}

	public function testGetName() {
		Assert::same('image.jpg', PathHelpers::getName('/foo/bar/image.jpg'));
		Assert::same('someDir', PathHelpers::getName('/foo/bar/someDir'));

		Assert::same('image.jpg', PathHelpers::getName('/foo\bar\image.jpg'));
		Assert::same('someDir', PathHelpers::getName('/foo\bar\someDir'));

		Assert::same('.htaccess', PathHelpers::getName('/foo\bar\.htaccess'));
	}

	public function testGetParentDir() {
		Assert::same('/foo', PathHelpers::getParentDir('/foo\bar'));
		Assert::same('/foo', PathHelpers::getParentDir('/foo/bar'));
		Assert::same('/foo\bar', PathHelpers::getParentDir('/foo\bar/lorem'));
		Assert::same('\foo/bar', PathHelpers::getParentDir('\foo/bar\lorem'));
	}

	public function testIsEqual() {
		$dir1 = $this->testsDir;
		$dir2 = $this->rootDir;
		Assert::false(PathHelpers::isEqual($dir1, $dir2));
		Assert::true(PathHelpers::isEqual($dir1, $dir1 . '/../tests'));
	}

	public function testIsSubdirOf() {
		Assert::true(PathHelpers::isSubdirOf($this->rootDir, $this->testsDir));
		Assert::true(PathHelpers::isSubdirOf($this->testsDir, $this->testsDir)); // not sure
		Assert::false(PathHelpers::isSubdirOf($this->testsDir, $this->rootDir));
	}

	public function testRename() {
		Assert::same('/foo/bar/xxx.txt', PathHelpers::rename('/foo/bar/file.txt', 'xxx.txt'));
		Assert::same('/foo/bar\xxx.txt', PathHelpers::rename('/foo/bar\file.txt', 'xxx.txt'));
	}

	public function testGetPath() {
		$rootDir = $this->container->getByType('Zax\Utils\RootDir');
		$path = PathHelpers::getPath('http://example.com', $rootDir, new \SplFileInfo(__DIR__ . '/PathHelpers.phpt'));
		Assert::same('http://example.com/tests/zax/Utils/PathHelpers.phpt', $path);
	}

}

$test = new PathHelpersTest($container);
$test->run();
