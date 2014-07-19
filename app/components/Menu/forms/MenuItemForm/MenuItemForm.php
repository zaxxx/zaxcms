<?php

namespace ZaxCMS\Components\Menu;
use Zax,
	Zax\Application\UI as ZaxUI,
	Nette,
	ZaxCMS;

class MenuItemForm extends Nette\Object {

	public function createMenuItemForm(ZaxUI\Control $control, $locale) {
		$f = $control->createForm();
		$f->addStatic('localeFlag', 'webContent.form.locale')
			->setDefaultValue($locale);

		$f->addText('name', 'menu.form.uniqueName')
			->setRequired()
			->addRule($f::PATTERN, 'form.error.alphanumeric', '([a-zA-Z0-9]+)');

		$f->addText('text', 'menu.form.text');
		$f->addText('htmlClass', 'menu.form.htmlClass');
		$f->addText('htmlTarget', 'menu.form.htmlTarget');


		$f->addText('href', 'menu.form.url');
		$f->addText('nhref', 'menu.form.nhref');
		$f->addNeonTextArea('nhrefParams', 'menu.form.nhrefParams');

		return $f;
	}

}