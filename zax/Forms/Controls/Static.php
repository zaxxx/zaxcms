<?php

namespace Zax\Forms\Controls;
use Nette,
	Nette\Forms\Helpers,
	Nette\Forms\Form,
	Nette\Utils\Html,
	Nette\Forms\Controls\BaseControl;

/**
 * Class StaticControl
 *
 * @package Zax\Forms\Controls
 */
class StaticControl extends BaseControl {

	protected $filters = [];

	public function loadHttpData() {

	}

	public function addFilter($callback) {
		$this->filters[] = $callback;
		return $this;
	}

	public function getControl() {
		$this->setOption('rendered', TRUE);

		$value = $this->value;
		foreach($this->filters as $filter) {
			$value = $filter($value);
		}
		$p = Html::el('p')->id($this->getHtmlId())->class('form-control-static form-group');
		if($value instanceof Html)
			return $p->setHtml($value);
		else
			return $p->setText((string)$value);
	}
}
 