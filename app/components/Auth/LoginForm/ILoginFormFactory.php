<?php

namespace ZaxCMS\Components\Auth;

interface ILoginFormFactory {

    /** @return LoginFormControl */
    public function create();

}


trait TInjectLoginFormFactory {

	protected $loginFormFactory;

	public function injectLoginFormFactory(ILoginFormFactory $loginFormFactory) {
		$this->loginFormFactory = $loginFormFactory;
	}

}

