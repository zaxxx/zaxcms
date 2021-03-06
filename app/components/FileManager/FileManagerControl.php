<?php

namespace ZaxCMS\Components\FileManager;
use Zax,
	Nette,
	DevModule;

/**
 * Class FileManagerControl
 *
 * It's a file manager component for general usage.
 *
 * @property-read DirectoryListControl $directoryList
 * @property-read FileListControl $fileList
 *
 * @method enableCreateDir()
 * @method enableRenameDir()
 * @method enableDeleteDir()
 * @method enableTruncateDir()
 * @method enableRenameFile()
 * @method enableRenameExtension()
 * @method enableDeleteFile()
 * @method enableUploadFIle()
 *
 * @package Zax\Components\FileManager
 */
class FileManagerControl extends FileManagerAbstract {

	use TInjectDirectoryListFactory,
		TInjectFileListFactory;

	/** @persistent */
	public $dir = '';

	/**
	 * @var
	 */
	protected $subdirs;

	/**
	 * @var array
	 */
	protected $featuresEnabled = [];

	/**
	 * @param $name
	 * @return $this
	 */
	public function enableFeature($name) {
		$this->featuresEnabled[] = $name;
		return $this;
	}

	/**
	 * @param array $features
	 * @return $this
	 */
	public function enableFeatures(array $features) {
		foreach($features as $feature) {
			$this->enableFeature($feature);
		}
		return $this;
	}

	/**
	 * @param $name
	 * @return bool
	 */
	public function isFeatureEnabled($name) {
		return in_array($name, $this->featuresEnabled);
	}

	/**
	 * @param $permissions
	 * @return $this
	 */
	public function setCreateDirPermissions($permissions) {
		$this->getDirectoryList()->setCreateDirPermissions($permissions);
		return $this;
	}

	/**
	 * @param array|string|NULL $mime
	 * @return $this
	 */
	public function setAllowedMimeType($mime=NULL) {
		$this->getFileList()->setAllowedMimeType($mime);
		return $this;
	}

	/**
	 * @param array|string|NULL $extensions
	 * @return $this
	 */
	public function setAllowedExtensions($extensions=NULL) {
		$this->getFileList()->setAllowedExtensions($extensions);
		return $this;
	}

	/**
	 * @param $message
	 * @return $this
	 */
	public function addUploadMessage($message) {
		$this->getFileList()->addUploadMessage($message);
		return $this;
	}

	public function isRootSelected() {
		$dir = realpath($this->getRoot() . $this->getDirectory());
		return Zax\Utils\PathHelpers::isEqual($this->getRoot(), $dir);
	}

	public function __call($method, $args = []) {
		if(strpos($method, 'enable') === 0) {

			$feature = lcfirst(substr($method, 6));
			return $this->enableFeature($feature);
		}
		parent::__call($method, $args);
	}

	/**
	 * @secured FileManager, Use
	 */
	public function viewDefault() {}

	public function beforeRender() {
		$this->template->currentDir = $this->getAbsoluteDirectory();
	}

	/**
	 * @return DirectoryListControl
	 */
	public function getDirectoryList() {
		return $this['directoryList'];
	}

	/**
	 * @return FileListControl
	 */
	public function getFileList() {
		return $this['fileList'];
	}

	/**
	 * @return $this|FileManagerControl
	 */
	public function getFileManager() {
		return $this;
	}
	/**
	 * @return DirectoryListControl
	 *
	 * @secured FileManager, Use
	 */
	protected function createComponentDirectoryList() {
		return $this->directoryListFactory->create();
	}

	/**
	 * @return FileListControl
	 *
	 * @secured FileManager, Use
	 */
	protected function createComponentFileList() {
		return $this->fileListFactory->create();
	}

	/**
	 * @param array $params
	 */
	public function loadState(array $params) {
		if(isset($params['dir'])) {
			$this->setDirectory($params['dir']);
		}
		parent::loadState($params);
	}

}
