<?php

namespace ZaxCMS\AdminModule;
use Nette,
	Zax\Application\UI,
	ZaxCMS,
	Zax;

class RolesPresenter extends BasePresenter {

	protected $rolesFactory;

	public function __construct(ZaxCMS\AdminModule\Components\Roles\IRolesFactory $rolesFactory) {
		$this->rolesFactory = $rolesFactory;
	}

	protected function createComponentRoles() {
	    return $this->rolesFactory->create()
		    ->enableAjax();
	}

}