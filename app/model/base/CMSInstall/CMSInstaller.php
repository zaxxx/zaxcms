<?php

namespace ZaxCMS\Model\CMSInstall;
use Zax,
	ZaxCMS,
	ZaxCMS\Model,
	Nette,
	Doctrine,
	Kdyby;

class CMSInstaller extends Nette\Object {

	protected $generator;

	protected $aclFacade;

	protected $menuInstall;

	protected $roleInstaller;

	protected $appDir;

	protected $tempDir;

	protected $installed;

	public function __construct($installed = FALSE,
								DatabaseGenerator $generator,
								Model\CMS\AclFactory $aclFacade,
								MenuInstaller $menuInstall,
								RoleInstaller $roleInstaller,
								Zax\Utils\AppDir $appDir,
								Zax\Utils\TempDir $tempDir) {
		$this->installed = $installed;
		$this->generator = $generator;
		$this->aclFacade = $aclFacade;
		$this->menuInstall = $menuInstall;
		$this->roleInstaller = $roleInstaller;
		$this->appDir = $appDir;
		$this->tempDir = $tempDir;
	}

	/**
	 * @throws CMSInstalledException
	 */
	public function checkIsInstalled() {
		if($this->isInstalled()) {
			throw new Zax\Model\CMSInstalledException;
		}
	}

	public function isInstalled() {
		return $this->installed;
	}

	protected function generateDatabase() {
		$this->generator->dropAndGenerate();
	}

	protected function buildRoles() {
		$this->roleInstaller->createDefaultRoles();
	}

	protected function buildAcl() {
		$this->aclFacade->createDefaultAcl();
	}

	protected function buildMenu() {
		$this->menuInstall->createDefaultMenu();
		$this->menuInstall->createAdminMenu();
	}

	public function wipeCache() {
		$paths = [
			realpath($this->tempDir . '/cache'),
			realpath($this->tempDir . '/proxy')
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

	public function prepareInstall() {
		$this->checkIsInstalled();
		$this->wipeCache();
		$this->generator->dropSchema();
	}

	public function install($step) {
		$this->generator->runSqlFromCache($step);
	}

	public function postInstall() {
		$this->buildRoles();
		$this->buildAcl();
		$this->buildMenu();
	}

}
