<?php

namespace ZaxCMS\AdminModule\Components\Users;
use Zax,
	Nette;

trait TRoleFilterable {

	protected $roleFilterFactory;

	public function injectRoleFilter(IRoleFilterFactory $roleFilterFactory) {
		$this->roleFilterFactory = $roleFilterFactory;
	}

	protected function createComponentRoleFilter() {
	    return $this->roleFilterFactory->create();
	}

	public function enableRoleFilter() {
		$this['roleFilter'];
		return $this;
	}

}