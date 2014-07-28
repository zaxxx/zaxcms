<?php

namespace Zax\Security;
use Zax,
	Latte,
	Nette;

class LattePermission extends Nette\Object {

	protected $permission;

	public function __construct(Zax\Security\IPermission $permission) {
		$this->permission = $permission;
	}

	public function install(Latte\Engine $latte) {
		$set = new Latte\Macros\MacroSet($latte->getCompiler());
		$set->addMacro('secured', [$this, 'macroSecured'], 'endif');
	}

	public function macroSecured(Latte\MacroNode $node, Latte\PhpWriter $writer) {
		return $writer->write('if ($acl->isUserAllowedTo(%node.args)):');
	}

}