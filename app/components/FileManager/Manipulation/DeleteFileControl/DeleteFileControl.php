<?php

namespace ZaxCMS\Components\FileManager;
use Zax,
	Nette,
	DevModule;

/**
 * Class DeleteFileControl
 *
 * @property-read Zax\Application\UI\Form $deleteForm
 * @property-read FileListControl $fileList
 *
 * @package ZaxCMS\Components\FileManager
 */
class DeleteFileControl extends FileManipulationControl {

	/**
	 * @var array
	 */
	public $onFileDelete = [];

	/**
	 * @return Zax\Application\UI\Form
	 */
	protected function createComponentDeleteForm() {
		$f = $this->createForm();
		$f->addHidden('file', $this->file);
		$f->addButtonSubmit('delete', 'common.button.delete', 'trash');
		$f->addButtonSubmit('cancel', '', 'remove');
		$f->addProtection();
		$f->enableBootstrap(
			[
				'danger'=>['delete'],
				'default'=>['cancel']
			], TRUE, 3, 'sm', 'form-inline');

		if($this->ajaxEnabled) {
			$f->enableAjax();
		}

		$f->onSuccess[] = [$this, 'deleteFormSubmitted'];

		return $f;
	}

	/**
	 * @param Nette\Application\UI\Form $form
	 * @param                           $values
	 */
	public function deleteFormSubmitted(Nette\Application\UI\Form $form, $values) {
		if($this->fileManager->isFeatureEnabled('deleteFile') && $form->submitted === $form['delete']) {
			$name = self::getPath($this, $values->file);
			Nette\Utils\FileSystem::delete($name);
			$this->onFileDelete($name);
			$this->flashMessage('fileManager.alert.fileDeleted', 'success');
		}
		$this->fileList->go('this', ['view' => 'Default']);
	}

	/**
	 * @return FileListControl
	 */
	public function getFileList() {
		return $this->lookup('ZaxCMS\Components\FileManager\FileListControl');
	}

	/**
	 * @return Zax\Application\UI\Form
	 */
	public function getDeleteForm() {
		return $this['deleteForm'];
	}

	public function viewDefault() {}

	public function beforeRender() {
		$this->template->file = $this->file;
	}

}