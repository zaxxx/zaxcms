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

	protected $appDir;

	public function __construct(DatabaseGenerator $generator,
								RoleService $roleService,
								MenuService $menuService,
								Zax\Utils\AppDir $appDir) {
		$this->generator = $generator;
		$this->roleService = $roleService;
		$this->menuService = $menuService;
		$this->appDir = $appDir;
	}

	protected function generateDatabase() {
		$this->generator->dropAndGenerate();
	}

	protected function buildRoles() {
		$this->roleService->createDefaultRoles();
	}

	protected function buildMenu() {
		$this->menuService->createDefaultMenu();
	}

	protected function wipeCache() {
		$paths = [
			realpath($this->appDir . '/temp/cache'),
			realpath($this->appDir . '/temp/proxy')
		];
		foreach(Nette\Utils\Finder::find('*')->in($paths) as $k => $file) {
			Nette\Utils\FileSystem::delete($k);
		}
	}

	public function install() {
		$this->wipeCache();

		$this->generateDatabase();
		$this->buildRoles();
		$this->buildMenu();
	}

}
