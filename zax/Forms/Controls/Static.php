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

	public function __construct($caption = NULL) {
		parent::__construct($caption);
		$this->control->setName('p');
	}

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
		$p = parent::getControl()->id($this->getHtmlId())->addClass('form-control-static');
		if($value instanceof Html)
			return $p->setHtml($value->addClass('control-label'));
		else
			return $p->setText(Html::el('span')->addClass('control-label')->setText((string)$value));
	}
}
 