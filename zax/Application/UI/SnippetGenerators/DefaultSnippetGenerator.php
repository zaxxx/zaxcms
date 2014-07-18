<?php

namespace Zax\Application\UI\SnippetGenerators;
use Zax,
	Nette;

/**
 * Class DefaultSnippetGenerator
 *
 * Default Nette implementation - 'snippet-flashMessage-'
 *
 * @package Zax\Application\UI\SnippetGenerators
 */
class DefaultSnippetGenerator implements ISnippetGenerator {

	public function getSnippetId(Nette\Application\UI\Control $control, $snippetName = NULL) {
		return 'snippet-' . $control->getUniqueId() . '-' . $snippetName;
	}

}