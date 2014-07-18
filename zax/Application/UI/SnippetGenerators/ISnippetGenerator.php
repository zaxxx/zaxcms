<?php

namespace Zax\Application\UI\SnippetGenerators;
use Zax,
	Nette;

interface ISnippetGenerator {

	public function getSnippetId(Nette\Application\UI\Control $control, $snippetName = NULL);

}