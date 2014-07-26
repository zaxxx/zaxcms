<?php

namespace ZaxCMS\Model;
use Zax,
	ZaxCMS,
	Nette,
	Doctrine,
	Kdyby;

class CMSInstaller extends Nette\Object {

	protected $generator;

	protected $aclFacade;

	protected $menuService;

	protected $appDir;

	public function __construct(DatabaseGenerator $generator,
								AclFacade $aclFacade,
								MenuService $menuService,
								Zax\Utils\AppDir $appDir) {
		$this->generator = $generator;
		$this->aclFacade = $aclFacade;
		$this->menuService = $menuService;
		$this->appDir = $appDir;
	}

	protected function generateDatabase() {
		$this->generator->dropAndGenerate();
	}

	protected function buildAcl() {
		$this->aclFacade->createDefaultAcl();
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
		$this->buildAcl();
		$this->buildMenu();
	}

}
