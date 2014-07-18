<?php

namespace Zax\Application\UI\SnippetGenerators;
use Zax,
	Nette\Application\UI as NetteUI,
	Nette;

/**
 * Class ShortSnippetGenerator
 *
 * Generates 'flashMessage' instead of 'snippet-flashMessage-'
 * Adds prefix 'p' when $control is presenter to prevent empty ID
 *
 * @package Zax\Application\UI\SnippetGenerators
 */
class ShortSnippetGenerator implements ISnippetGenerator {

	public function getSnippetId(NetteUI\Control $control, $snippetName = NULL) {
		return ($control instanceof NetteUI\Presenter ? 'p' : $control->getUniqueId()) . ($snippetName === NULL || strlen($snippetName) === 0 ? '' : '-' . $snippetName);
	}

}