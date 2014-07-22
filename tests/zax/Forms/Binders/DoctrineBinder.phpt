<?php

namespace Zax\Tests\Forms\Binders;
use Tester,
	Tester\Assert,
	Nette,
	Zax,
	ZaxCMS;

$container = require __DIR__ . '/../../../bootstrap.php';

/**
 * @property mixed $foo
 * @property mixed $bar
 */
class FooEntity extends Nette\Object {

	protected $foo;

	protected $bar;

	public function getFoo() {
		return $this->foo;
	}

	public function setFoo($foo) {
		$this->foo = $foo;
	}

	public function getBar() {
		return $this->bar;
	}

	public function setBar($bar) {
		$this->bar = $bar;
	}

}

class DoctrineBinderTest extends Tester\TestCase {

	/** @var Nette\DI\Container */
	private $container;

	/** @var Zax\Forms\DoctrineBinder */
	private $binder;

	/** @var Zax\Application\UI\FormFactory */
	private $formFactory;

	public function __construct(Nette\DI\Container $container) {
		$this->container = $container;
		$this->binder = new Zax\Forms\DoctrineBinder;
		$this->formFactory = $container->getByType('Zax\Application\UI\FormFactory');
	}

	public function testDoctrineBinder() {
		$form = $this->formFactory->create();

		$form->addText('foo');
		$form->addText('bar');

		$entity = new FooEntity;
		$entity->foo = 'hello';
		$entity->bar = 'world';

		$this->binder->entityToForm($entity, $form);

		Assert::same('hello', $form['foo']->getValue());
		Assert::same('world', $form['bar']->getValue());

		$entity2 = new FooEntity;

		$entity2 = $this->binder->formToEntity($form, $entity2);

		Assert::same('hello', $entity2->foo);
		Assert::same('world', $entity->bar);
	}

}

$test = new DoctrineBinderTest($container);
$test->run();
