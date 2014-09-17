<?php

namespace Zax\Tracy\Panels;
use Zax,
	Tracy,
	Latte,
	Nette;

abstract class Panel extends Nette\Object implements Tracy\IBarPanel {

	protected $latteFactory;

	public function __construct(Nette\Bridges\ApplicationLatte\ILatteFactory $latteFactory) {
		$this->latteFactory = $latteFactory;
	}

	/** @return Nette\Bridges\ApplicationLatte\Template */
	abstract protected function createTab();

	/** @return Nette\Bridges\ApplicationLatte\Template */
	abstract protected function createPanel();

	protected function createTemplate($name) {
		$latte = $this->latteFactory->create();
		return (new Nette\Bridges\ApplicationLatte\Template($latte))->setFile(dirname($this->reflection->fileName) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $name . '.latte');
	}

	protected function createForm() {
		$form = new Nette\Forms\Form;
		$form->renderer->wrappers['pair']['container'] = NULL;
		$form->renderer->wrappers['control']['container'] = NULL;
		$form->renderer->wrappers['label']['container'] = NULL;
		return $form;
	}

	public function getTab() {
		$tpl = $this->createTab();
		ob_start();
		$tpl->render();
		return ob_get_clean();
	}

	public function getPanel() {
		$tpl = $this->createPanel();
		ob_start();
		$tpl->render();
		return ob_get_clean();
	}

}