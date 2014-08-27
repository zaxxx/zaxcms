<?php

namespace Zax\Html\Icons;
use Zax,
	Latte,
	Nette;

class LatteIcons extends Nette\Object {

	protected $icons;

	public function __construct(Zax\Html\Icons\IIcons $icons) {
		$this->icons = $icons;
	}

	public function install(Latte\Engine $latte) {
		$set = new Latte\Macros\MacroSet($latte->getCompiler());
		$set->addMacro('icon', [$this, 'macroIcon']);
	}

	public function macroIcon(Latte\MacroNode $node, Latte\PhpWriter $writer) {
		return $writer->write('echo $icons->getIcon(%node.word)');
	}

}