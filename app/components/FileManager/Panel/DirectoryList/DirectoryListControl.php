<?php

namespace ZaxCMS\Components\FileManager;
use Nette,
	Zax,
	Zax\Application\UI\SecuredControl;

/**
 * Class DirectoryListControl
 *
 * @property-read CreateDirControl $createDir;
 * @property-read RenameDirControl $renameDir;
 * @property-read DeleteDirControl $deleteDir;
 *
 * @package ZaxCMS\Components\FileManager
 */
class DirectoryListControl extends FileManagerAbstract {

	/** @persistent */
	public $usageInfo = FALSE;

	/**
	 * @var ICreateDirFactory
	 */
	protected $createDirFactory;

	/**
	 * @var IRenameDirFactory
	 */
	protected $renameDirFactory;

	/**
	 * @var IDeleteDirFactory
	 */
	protected $deleteDirFactory;

	/**
	 * @var int
	 */
	protected $createDirPermissions = 0766;

	/**
	 * @param ICreateDirFactory $createDirFactory
	 * @param IRenameDirFactory $renameDirFactory
	 * @param IDeleteDirFactory $deleteDirFactory
	 */
	public function __construct(
		ICreateDirFactory $createDirFactory,
		IRenameDirFactory $renameDirFactory,
		IDeleteDirFactory $deleteDirFactory
	) {
		$this->createDirFactory = $createDirFactory;
		$this->renameDirFactory = $renameDirFactory;
		$this->deleteDirFactory = $deleteDirFactory;
	}


	/**
	 * @param $permissions
	 * @return $this
	 */
	public function setCreateDirPermissions($permissions) {
		$this->createDirPermissions = $permissions;
		return $this;
	}


	public function viewDefault() {}

	public function viewCreateDir() {
		$this->template->createDir = TRUE;
	}

	public function viewRenameDir() {
		$this->template->renameDir = TRUE;
	}

	public function viewDeleteDir() {
		$this->template->deleteDir = TRUE;
	}

	public function beforeRender() {
		$dir = $this->getAbsoluteDirectory();

		if($this->usageInfo) {
			$rootSize = 0;
			$rootCountFiles = 0;
			foreach(Nette\Utils\Finder::findFiles('*')->from($this->getRoot()) as $file) {
				$rootSize += $file->getSize();
				$rootCountFiles++;
			}
			$subSizes = [];
			$subCountFiles = [];
			foreach($this->getSubdirectories() as $path => $subdir) {
				$subSizes[$path] = 0;
				$subCountFiles[$path] = 0;
				foreach(Nette\Utils\Finder::findFiles('*')->from($subdir) as $file) {
					$subSizes[$path] += $file->getSize();
					$subCountFiles[$path]++;
				}
			}
			$this->template->subSizes = $subSizes;
			$this->template->rootSize = $rootSize;
			$this->template->rootCountFiles = $rootCountFiles;
			$this->template->subCountFiles = $subCountFiles;
		}

		$this->template->rootDir = $this->getRoot();
		$this->template->currentDir = $dir;
		$this->template->fileSystemDirs = $this->getSubdirectories();
		$this->template->usageInfo = $this->usageInfo;
	}

	/**
	 * @return CreateDirControl
	 */
	public function getCreateDir() {
		return $this['createDir'];
	}

	/**
	 * @return RenameDirControl
	 */
	public function getRenameDir() {
		return $this['renameDir'];
	}

	/**
	 * @return DeleteDirControl
	 */
	public function getDeleteDir() {
		return $this['deleteDir'];
	}

	/**
	 * @return CreateDirControl|NULL
	 */
	protected function createComponentCreateDir() {
		if($this->fileManager->isFeatureEnabled('createDir')) {
			return $this->createDirFactory->create()
				->setCreateDirPermissions($this->createDirPermissions);
		}
	}

	/**
	 * @return RenameDirControl|NULL
	 */
	protected function createComponentRenameDir() {
		if($this->fileManager->isFeatureEnabled('renameDir')) {
			return $this->renameDirFactory->create();
		}
	}

	/**
	 * @return DeleteDirControl|NULL
	 */
	protected function createComponentDeleteDir() {
		if($this->fileManager->isFeatureEnabled('deleteDir')) {
			return $this->deleteDirFactory->create();
		}
	}

}