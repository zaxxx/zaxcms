<?php

namespace ZaxCMS\AdminModule\Components\Mails;

interface IEditMailFormFactory {

    /** @return EditMailFormControl */
    public function create();

}


trait TInjectEditMailFormFactory {

	/** @var IEditMailFormFactory */
	protected $editMailFormFactory;

	public function injectEditMailFormFactory(IEditMailFormFactory $editMailFormFactory) {
		$this->editMailFormFactory = $editMailFormFactory;
	}

}

