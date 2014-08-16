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

	protected $installed;

	public function __construct($installed = FALSE,
								DatabaseGenerator $generator,
								AclFacade $aclFacade,
								MenuService $menuService,
								Zax\Utils\AppDir $appDir) {
		$this->installed = $installed;
		$this->generator = $generator;
		$this->aclFacade = $aclFacade;
		$this->menuService = $menuService;
		$this->appDir = $appDir;
	}

	/**
	 * @throws CMSInstalledException
	 */
	public function checkIsInstalled() {
		if($this->isInstalled()) {
			throw new CMSInstalledException;
		}
	}

	public function isInstalled() {
		return $this->installed;
	}

	protected function generateDatabase() {
		$this->generator->dropAndGenerate();
	}

	protected function buildAcl() {
		$this->aclFacade->createDefaultAcl();
	}

	protected function buildMenu() {
		$this->menuService->createDefaultMenu();
		$this->menuService->createAdminMenu();
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

	public function saveInstalledFlag() {
		$file = $this->appDir . '/config/zaxcmsinstalled.neon';
		$config = Nette\Neon\Neon::decode(file_get_contents($file));
		$config['parameters']['CMSInstalled'] = TRUE;
		file_put_contents($file, Nette\Neon\Neon::encode($config, Nette\Neon\Encoder::BLOCK));
	}

	public function install() {

		$this->checkIsInstalled();

		$this->wipeCache();

		$this->generateDatabase();
		$this->buildAcl();
		$this->buildMenu();
	}

}
