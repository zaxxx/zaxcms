<?php

namespace Zax\Forms;
use Zax,
	Nette,
	Nette\Utils\Html;

class DefaultLabelFactory extends Nette\Object implements ILabelFactory {

	protected $icons;

	protected $translator;

	protected $prototype;

	public function __construct(Zax\Html\Icons\IIcons $icons, Nette\Localization\ITranslator $translator) {
		$this->icons = $icons;
		$this->translator = $translator;
		$this->prototype = Html::el();
	}

	public function getLabel($text, $hint = NULL, $hintIcon = 'question-circle') {
		$proto = clone $this->prototype;

		$proto->add($this->translator->translate($text));

		if($hint !== NULL) {
			$proto->add(' ')->add(
				Html::el('a')
					->title($this->translator->translate($hint))
					->add($this->icons->getIcon($hintIcon))
			);
		}

		return $proto;
	}

}