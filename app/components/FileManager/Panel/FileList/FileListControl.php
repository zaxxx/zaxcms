<?php

namespace ZaxCMS\Components\FileManager;
use Nette,
	Zax,
	Zax\Application\UI\Control;

/**
 * Class FileListControl
 *
 * @property-read RenameFileControl $renameFile
 * @property-read DeleteFileControl $deleteFile
 *
 * @package ZaxCMS\Components\FileManager
 */
class FileListControl extends FileManagerAbstract {

	use TInjectRenameFileFactory,
		TInjectDeleteFileFactory,
		TInjectUploadFileFactory,
		Zax\Utils\TInjectRootDir;

	/**
	 * @var
	 */
	protected $renaming;

	/**
	 * @var
	 */
	protected $deleting;

	/**
	 * @var string|array|NULL
	 */
	protected $mime;

	/**
	 * @var array
	 */
	protected $uploadMessages = [];

	/**
	 * @var string|array|NULL
	 */
	protected $extensions;

	/** @secured FileManager, Use */
	public function viewDefault() {

	}

	/** @secured FileManager, Edit */
	public function viewRenameFile($file) {
		$this->template->renameFile = $this->renaming = $file;
	}

	/** @secured FileManager, Delete */
	public function viewDeleteFile($file) {
		$this->template->deleteFile = $this->deleting = $file;
	}

	/** @secured FileManager, Upload */
	public function viewUploadFile() {
		$this->template->uploadFile = TRUE;
		$this->template->uploadMessages = $this->uploadMessages;
	}

	public function beforeRender() {
		$dir = $this->getAbsoluteDirectory();

		$this->template->rootDir = $this->getRoot();
		$this->template->rootDirProvider = $this->rootDir;
		$this->template->fileSystemCurrent = Nette\Utils\Finder::findFiles('*')->in($dir);
	}

	/**
	 * @return RenameFileControl
	 */
	public function getRenameFile() {
		return $this['renameFile'];
	}

	/**
	 * @return DeleteFileControl
	 */
	public function getDeleteFile() {
		return $this['deleteFile'];
	}

	/**
	 * @return UploadFileControl
	 */
	public function getUploadFile() {
		return $this['uploadFile'];
	}

	/**
	 * @param $message
	 * @return $this
	 */
	public function addUploadMessage($message) {
		$this->uploadMessages[] = $message;
		return $this;
	}

	/**
	 * @param array|string|NULL $mime
	 * @return $this
	 */
	public function setAllowedMimeType($mime=NULL) {
		$this->mime = $mime;
		return $this;
	}

	/**
	 * @param array|string|NULL $extensions
	 * @return $this
	 */
	public function setAllowedExtensions($extensions=NULL) {
		$this->extensions = $extensions;
		return $this;
	}

	/**
	 * @return RenameFileControl|NULL
	 *
	 * @secured FileManager, Edit
	 */
	protected function createComponentRenameFile() {
		if($this->fileManager->isFeatureEnabled('renameFile')) {
			return $this->renameFileFactory->create()
				->setFile($this->renaming);
		}
	}

	/**
	 * @return DeleteFileControl|NULL
	 *
	 * @secured FileManager, Delete
	 */
	protected function createComponentDeleteFile() {
		if($this->fileManager->isFeatureEnabled('deleteFile')) {
			return $this->deleteFileFactory->create()
				->setFile($this->deleting);
		}
	}

	/**
	 * @return UploadFileControl|NULL
	 *
	 * @secured FileManager, Upload
	 */
	protected function createComponentUploadFile() {
		if($this->fileManager->isFeatureEnabled('uploadFile')) {
			return $this->uploadFileFactory->create()
				->disableAjaxFor(['uploadForm'])
				->setAllowedMimeType($this->mime)
				->setAllowedExtensions($this->extensions)
				->setUploadMessages($this->uploadMessages);
		}
	}

}