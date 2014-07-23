<?php

namespace ZaxCMS\Components\FileManager;
use Zax,
	Nette,
	DevModule;

/**
 * Class CreateDirControl
 *
 * @property-read Zax\Application\UI\Form $createForm
 * @property-read DirectoryListControl $directoryList
 *
 * @package ZaxCMS\Components\FileManager
 */
class CreateDirControl extends DirectoryManipulationControl {

	/**
	 * @var array
	 */
	public $onDirCreate = [];

	/**
	 * @var int
	 */
	protected $createDirPermissions = 0766;

	/**
	 * @param $permissions
	 * @return $this
	 */
	public function setCreateDirPermissions($permissions) {
		$this->createDirPermissions = $permissions;
		return $this;
	}

	/**
	 * @return Zax\Application\UI\Form
	 */
	protected function createComponentCreateForm() {
		$f = $this->createForm();
		$f->addText('name', '');
		$f->addButtonSubmit('create', '', 'ok');
		$f->addButtonSubmit('cancel', '', 'remove');

		$f->autofocus('name');

		$f->addProtection();
		$f->enableBootstrap(
			[
				'success'=>['create'],
				'default'=>['cancel']
			], TRUE, 3, 'sm', 'form-inline');

		$f->onSuccess[] = [$this, 'createFormSubmitted'];

		return $f;
	}

	/**
	 * @param Nette\Application\UI\Form $form
	 * @param                           $values
	 */
	public function createFormSubmitted(Nette\Application\UI\Form $form, $values) {
		if($this->fileManager->isFeatureEnabled('createDir') && $form->submitted === $form['create']) {
			$dir = self::getPath($this, $values->name);
			Nette\Utils\FileSystem::createDir($dir, $this->createDirPermissions);
			$this->onDirCreate($dir);
			$this->flashMessage('fileManager.alert.dirCreated', 'success');
		}
		$this->directoryList->go('this', ['view' => 'Default']);
	}

	/**
	 * @return DirectoryListControl
	 */
	public function getDirectoryList() {
		return $this->lookup('ZaxCMS\Components\FileManager\DirectoryListControl');
	}

	/**
	 * @return Zax\Application\UI\Form
	 */
	public function getCreateForm() {
		return $this['createForm'];
	}

	public function viewDefault() {}

	public function beforeRender() {
	}

}