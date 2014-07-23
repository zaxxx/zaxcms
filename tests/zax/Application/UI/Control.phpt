<?php

namespace Zax\Tests\Application\UI;
use Tester,
	Tester\Assert,
	Nette,
	Zax,
	ZaxCMS;

$container = require __DIR__ . '/../../../bootstrap.php';

class ControlTest extends Tester\TestCase {

	/** @var Nette\DI\Container */
	private $container;

	/** @var Zax\Application\UI\Control */
	private $control;

	/** @var Zax\Application\UI\Presenter */
	private $presenter;

	public function __construct(Nette\DI\Container $container) {
		$this->container = $container;
		$pFactory = $container->getByType('Nette\Application\IPresenterFactory');
		$this->presenter = $pFactory->createPresenter('Test:Test');
	}

	public function setUp() {
		if($this->control !== NULL ){
			$this->presenter->removeComponent($this->control);
		}
		$this->control = $this->presenter->getComponent('testComponent');
	}

	public function testViewNotExist() {
		$this->control->setView('abc');
		Assert::exception(function() {$this->control->run();}, 'Nette\Application\UI\BadSignalException');
	}

	public function testBeforeRenderNotExist() {
		$this->control->setView('foo');
		Assert::exception(function() {$this->control->run('bad');}, 'Nette\Application\UI\BadSignalException');
	}

	public function testTemplatePath() {
		$path1 = $this->control->getTemplatePath('Foo');
		Assert::same($this->tp('Foo'), $path1);

		$path2 = $this->control->getTemplatePath('Foo', 'Bar');
		Assert::same($this->tp('Foo.Bar'), $path2);
	}

	public function testView() {
		$this->control->setView('foo');
		Assert::exception(function() {$this->control->run();}, 'RuntimeException', 'Missing template file \'' . $this->tp('Foo') . '\'.');
	}

	public function testTemplateVars() {
		$this->control->enableAjax();
		$this->control->setView('foo');

		$template = $this->control->createTemplate();
		Assert::true($template->ajaxEnabled);
		Assert::same('Foo', $template->view);
	}

	public function testRenderHack() {
		$this->control->setView('foo');
		Assert::exception(function() {$this->control->render();}, 'RuntimeException', 'Missing template file \'' . $this->tp('Foo') . '\'.');
		Assert::exception(function() {$this->control->renderBar();}, 'RuntimeException', 'Missing template file \'' . $this->tp('Foo.Bar') . '\'.');
	}

	public function testAjaxRecursive() {
		$this->control->enableAjax();
		$control = $this->control;
		for($i=0;$i<100;$i++) {
			Assert::true($control->isAjaxEnabled());
			$control = $control->getComponent('testComponent');
		}
	}

	public function testDisableAjaxFor() {
		$this->control->enableAjax();

		$this->control[str_repeat('testComponent-', 49) . 'testComponent']
			->disableAjaxFor(['testComponent']);

		$control = $this->control;
		for($i=0;$i<100;$i++) {
			if($i>50) {
				Assert::false($control->isAjaxEnabled());
			} else {
				Assert::true($control->isAjaxEnabled());
			}
			$control = $control->getComponent('testComponent');
		}
	}

	private function tp($name) {
		return dirname($this->control->reflection->fileName) . '/templates/' . $name . '.latte';
	}

}

$test = new ControlTest($container);
$test->run();
