<?php

namespace ZaxCMS\Components\FileManager;
use Zax,
	Nette,
	DevModule;

/**
 * Class UploadFileControl
 *
 * @property-read FileListControl $fileList
 *
 * @package Zax\Components\FileManager
 */
class UploadFileControl extends FileManipulationControl {

	/**
	 * @var array
	 */
	public $onFileRename = [];

	/**
	 * @var string|array|NULL
	 */
	protected $mime;

	/**
	 * @var string|array|NULL
	 */
	protected $extensions;

	/**
	 * @var array|NULL
	 */
	protected $messages;

	/**
	 * @param string|array|NULL $mime
	 * @return $this
	 */
	public function setAllowedMimeType($mime = NULL) {
		$this->mime = $mime;
		return $this;
	}

	/**
	 * @param string|array|NULL $extensions
	 * @return $this
	 */
	public function setAllowedExtensions($extensions = NULL) {
		$this->extensions = $extensions;
		return $this;
	}

	/**
	 * @param array $messages
	 * @return $this
	 */
	public function setUploadMessages($messages) {
		$this->messages = $messages;
		return $this;
	}

	/**
	 *
	 */
	public function handleCancel() {
		$this->fileList->go('this', ['view' => 'Default']);
	}

	/**
	 * @return Zax\Application\UI\Form
	 */
	protected function createComponentUploadForm() {
		$f = $this->createForm();

		$f->addUpload('file', 'fileManager.form.file')
			->addRule(Zax\Application\UI\Form::MAX_FILE_SIZE, NULL, Zax\Utils\HttpHelpers::getMaxUploadSize()*1024*1024);

		$f->addButtonSubmit('upload', 'fileManager.button.upload', 'upload');
		if($this->mime !== NULL) {
			$f['file']->addRule(Zax\Application\UI\Form::MIME_TYPE, NULL, $this->mime);
		}
		if($this->extensions !== NULL) {
			$f['file']->addRule('Zax\\Forms\\Validators\\UploadValidator::validateExtension', NULL, $this->extensions);
		}
		$f->addLinkSubmit('cancel', '', 'remove', $this->link('cancel!'))
			->getControlPrototype()->addClass('ajax');

		$f->addProtection();
		$f->enableBootstrap([
			'success'=>['upload'],
			'default'=>['cancel']
		], TRUE, 3, 'sm');


		$f->onSuccess[] = [$this, 'uploadFormSubmitted'];
		$f->onError[] = function() {
			$this->flashMessage('fileManager.alert.fileNotUploaded', 'danger');
		};

		return $f;
	}

	/**
	 * @param Nette\Application\UI\Form $form
	 * @param                           $values
	 */
	public function uploadFormSubmitted(Nette\Application\UI\Form $form, $values) {
		/** @var Nette\Http\FileUpload $file */
		if($form->submitted === $form['upload']) {
			$file = $values->file;
			if($file->isOk()) {
				$file->move($this->getFileManager()->getAbsoluteDirectory() . '/' . $file->getSanitizedName());
				$this->flashMessage('fileManager.alert.fileUploaded', 'success');
			}
		}
		$this->fileList->go('this', ['view' => 'Default']);
	}

	/**
	 * @return Zax\Components\FileManager\FileListControl
	 */
	public function getFileList() {
		return $this->lookup('Zax\Components\FileManager\FileListControl');
	}

	public function viewDefault() {}

	public function beforeRender() {
		$this->template->mime = $this->mime;
		$this->template->messages = $this->messages;
	}

}