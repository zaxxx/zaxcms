<?php

namespace ZaxCMS\AdminModule\Components\Users;

interface ISecurityInfoFactory {

    /** @return SecurityInfoControl */
    public function create();

}


trait TInjectSecurityInfoFactory {

	protected $securityInfoFactory;

	public function injectSecurityInfoFactory(ISecurityInfoFactory $securityInfoFactory) {
		$this->securityInfoFactory = $securityInfoFactory;
	}

}

