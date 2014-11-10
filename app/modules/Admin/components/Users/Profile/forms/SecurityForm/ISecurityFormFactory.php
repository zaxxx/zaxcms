<?php

namespace ZaxCMS\AdminModule\Components\Users;

interface ISecurityFormFactory {

    /** @return SecurityFormControl */
    public function create();

}


trait TInjectSecurityFormFactory {

	protected $securityFormFactory;

	public function injectSecurityFormFactory(ISecurityFormFactory $securityFormFactory) {
		$this->securityFormFactory = $securityFormFactory;
	}

}

