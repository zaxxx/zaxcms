<?php

namespace ZaxCMS\Model;
use Zax,
	ZaxCMS,
	Nette,
	Doctrine,
	Kdyby;

class CMSInstaller extends Nette\Object {

	protected $generator;

	protected $roleService;

	protected $menuService;

	public function __construct(DatabaseGenerator $generator,
								RoleService $roleService,
								MenuService $menuService) {
		$this->generator = $generator;
		$this->roleService = $roleService;
		$this->menuService = $menuService;
	}

	protected function generateDatabase() {
		$this->generator->dropAndGenerate();
	}

	protected function buildRoles() {
		$this->roleService->getDeveloperRole();
	}

	protected function buildMenu() {
		$this->menuService->generateDefaultMenu();
	}

	public function install() {
		$this->generateDatabase();
		$this->buildRoles();
		$this->buildMenu();
	}

}
