<?php

namespace ZaxCMS\Components\WebContent;
use Nette,
	Zax,
	ZaxCMS,
	ZaxCMS\Model,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Zax\Application\UI\SecuredControl;

class EditControl extends SecuredControl {

	protected $webContent;

	/**
	 * @var Model\WebContentService
	 */
	protected $webContentService;

	/**
	 * @var IEditFormFactory
	 */
	protected $editFormFactory;

	/**
	 * @var ZaxCMS\Components\FileManager\IFileManagerFactory
	 */
	protected $fileManagerFactory;

	/**
	 * @var Zax\Utils\RootDir
	 */
	protected $rootDir;

	/** @persistent */
	public $locale;

	/**
	 * @param IEditFormFactory                               $editFormFactory
	 * @param Model\WebContentService                        $webContentService
	 * @param ZaxCMS\Components\FileManager\IFileManagerFactory $fileManagerFactory
	 * @param Zax\Utils\RootDir                              $rootDir
	 */
	public function __construct(IEditFormFactory $editFormFactory,
								Model\WebContentService $webContentService,
								ZaxCMS\Components\FileManager\IFileManagerFactory $fileManagerFactory,
								Zax\Utils\RootDir $rootDir) {
		$this->editFormFactory = $editFormFactory;
		$this->webContentService = $webContentService;
		$this->fileManagerFactory = $fileManagerFactory;
		$this->rootDir = $rootDir;
	}

	public function getLocale() {
		if($this->locale === NULL || !in_array($this->locale, $this->getAvailableLocales())) {
			return parent::getLocale();
		}
		return $this->locale;
	}

	public function setWebContent(Model\WebContent $webContent) {
		$this->webContent = $webContent;
		return $this;
	}

	public function getWebContent() {
		return $this->webContent;
	}

	/**
	 * @secured WebContent, Edit
	 */
	public function viewDefault() {

	}

	/**
	 * @secured FileManager, Show
	 */
	public function viewFiles() {

	}

	public function beforeRender() {
		$this->template->availableLocales = $this->getAvailableLocales();
		$this->template->currentLocale = $this->getLocale();
		$this->template->webContent = $this->getWebContent();
	}

	public function handleClose() {
		$this->close();
	}

	public function close() {
		$this->parent->go('this', ['view' => 'Default']);
	}

	public function handleCancel() {
		$this->close();
	}

	/**
	 * @secured WebContent, Edit
	 */
	protected function createComponentEditForm() {
		return $this->editFormFactory->create()
			->setService($this->webContentService)
			->setWebContent($this->getWebContent());
	}


	/**
	 * @secured FileManager, Show
	 */
	protected function createComponentFileManager() {
		return $this->fileManagerFactory->create()
			->setRoot($this->rootDir . '/upload/webContent/' . $this->webContent->name)
			->enableFeatures(
				[
					'createDir',
					'renameDir',
					'deleteDir',
					'truncateDir',
					'uploadFile',
					'renameFile',
					'deleteFile',
					'linkFile'
				]
			);
	}

}