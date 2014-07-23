<?php

namespace ZaxCMS\AdminModule;
use Nette,
    Zax\Application\UI,
	ZaxCMS,
    Zax;

class DefaultPresenter extends BasePresenter {

	protected $generator;

	protected $menuService;

	protected $appDir;

	public function injectAppDir(Zax\Utils\AppDir $appDir) {
		$this->appDir = $appDir;
	}

	public function injectDatabaseGenerator(ZaxCMS\Model\DatabaseGenerator $generator) {
		$this->generator = $generator;
	}

	public function injectModel(ZaxCMS\Model\MenuService $menuService) {
		$this->menuService = $menuService;
	}

	private function generate($all = FALSE) {
		$this->generator->dropAndGenerate($all ? [] :
			[
				'ZaxCMS\Model\Permission',
				'ZaxCMS\Model\Privilege',
				'ZaxCMS\Model\Resource',
				'ZaxCMS\Model\Role',
				'ZaxCMS\Model\User',
				//'ZaxCMS\Model\WebContent',
			]
		);
	}

	public function handleClearCache() {
		$paths = [
			realpath($this->appDir . '/temp/cache')
		];
		foreach(Nette\Utils\Finder::find('*')->in($paths) as $k => $file) {
			Nette\Utils\FileSystem::delete($k);
		}
		$this->flashMessage('system.alert.cacheCleared', 'success');
		$this->redirect('this');
	}

	public function handleRegenerateDatabase() {
		$this->generate();
		$this->flashMessage('system.alert.databaseRegenerated', 'success');
		$this->redirect('this');
	}

	public function handleRegenerateDatabaseAll() {
		$this->generate(TRUE);
		$this->flashMessage('system.alert.databaseRegenerated', 'success');
		$this->redirect('this');
	}

	public function handleGenerateMenu() {
		$this->menuService->generateDefaultMenu();
		$this->flashMessage('menu regenerated', 'success');
		$this->redirect('this');
	}


}