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
		$set->addMacro('secured', [$this, 'macroSecured'], '}');
	}

	public function macroSecured(Latte\MacroNode $node, Latte\PhpWriter $writer) {
		if($node->prefix === $node::PREFIX_TAG) {
			return $writer->write($node->htmlNode->closing ? 'if(array_pop($_l->secured)){' : 'if($_l->secured[] = $acl->isUserAllowedTo(%node.args)) {');
		}
		return $writer->write('if ($acl->isUserAllowedTo(%node.args)) {');
	}

}