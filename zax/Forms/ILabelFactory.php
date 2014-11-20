<?php

namespace Zax\Forms;
use Zax,
	Nette;

interface ILabelFactory {

	public function getLabel($text, $hint = NULL, $hintIcon = 'question-circle');

}