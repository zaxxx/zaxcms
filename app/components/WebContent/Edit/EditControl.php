<?php

namespace ZaxCMS\Components\WebContent;
use Nette,
    Zax,
	ZaxCMS\Model,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class EditControl extends Control {

	/**
	 * @var Model\WebContent
	 */
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
	 * @var Zax\Components\FileManager\IFileManagerFactory
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
	 * @param Zax\Components\FileManager\IFileManagerFactory $fileManagerFactory
	 * @param Zax\Utils\RootDir                              $rootDir
	 */
	public function __construct(IEditFormFactory $editFormFactory,
								Model\WebContentService $webContentService,
	                            Zax\Components\FileManager\IFileManagerFactory $fileManagerFactory,
	                            Zax\Utils\RootDir $rootDir) {
		$this->editFormFactory = $editFormFactory;
		$this->webContentService = $webContentService;
		$this->fileManagerFactory = $fileManagerFactory;
		$this->rootDir = $rootDir;
	}

	/**
	 * @param Model\WebContent $webContent
	 * @return $this
	 */
	public function setWebContent(Model\WebContent $webContent) {
		$this->webContent = $webContent;
		return $this;
	}

    public function viewDefault() {
        
    }
	
	public function viewFiles() {
	    
	}
    
    public function beforeRender() {
		$this->template->availableLocales = $this->getAvailableLocales();
	    $this->template->currentLocale = $this->getLocale();
	    $this->template->webContent = $this->webContent;
    }

	public function handleClose() {
		$this->close();
	}

	public function close() {
		$this->webContent->setTranslatableLocale($this->translator->locale);
		$this->webContentService->getEm()->refresh($this->webContent);
		$this->parent->go('this', ['view' => 'Default', 'edit-locale' => NULL, 'edit-view' => 'Default']);
	}

	public function handleCancel() {
		$this->close();
	}

	/**
	 * @return ZaxUI\Form
	 */
	protected function createComponentEditForm() {
		return $this->editFormFactory->create()
			->enableAjax(!$this->autoAjax)
			->setService($this->webContentService)
			->setWebContent($this->webContent);
	}

	/**
	 * @return Zax\Components\FileManager\FileManagerControl
	 */
	protected function createComponentFileManager() {
		return $this->fileManagerFactory->create()
			->setRoot($this->rootDir . '/upload/webContent/' . $this->webContent->name)
			->enableAjax($this->autoAjax)
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