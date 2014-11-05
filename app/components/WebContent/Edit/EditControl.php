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

	use TInjectEditFormFactory,
		Model\CMS\Service\TInjectWebContentService,
		ZaxCMS\Components\FileManager\TInjectFileManagerFactory,
		ZaxCMS\Components\LocaleSelect\TInjectLocaleSelectFactory,
		Zax\Utils\TInjectRootDir;

	protected $webContent;

	public function getLocale() {
		return $this['localeSelect']->getLocale();
	}

	public function setWebContent(Model\CMS\Entity\WebContent $webContent) {
		$this->webContent = $webContent;
		return $this;
	}

	public function getWebContent() {
		return $this->webContent;
	}

	/**
	 * @secured WebContent, Use
	 */
	public function viewDefault() {

	}

	/**
	 * @secured FileManager, Use
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
	protected function createComponentLocaleSelect() {
	    return $this->localeSelectFactory->create();
	}

	/**
	 * @secured WebContent, Edit
	 */
	protected function createComponentEditForm() {
		return $this->editFormFactory->create()
			->setWebContent($this->getWebContent());
	}


	/**
	 * @secured FileManager, Use
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