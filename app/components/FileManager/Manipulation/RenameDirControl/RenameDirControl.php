<?php

namespace ZaxCMS\Components\FileManager;
use Zax,
	Nette,
	DevModule;

/**
 * Class RenameDirControl
 *
 * @property-read Zax\Application\UI\Form $renameForm
 * @property-read DirectoryListControl $directoryList
 *
 * @package ZaxCMS\Components\FileManager
 */
class RenameDirControl extends DirectoryManipulationControl {

	/**
	 * @var array
	 */
	public $onDirRename = [];

	public function handleCancel() {
		$this->fileManager->go('this', ['view' => 'Default', 'directoryList-view' => 'Default']);
	}

	/**
	 * @return Zax\Application\UI\Form
	 */
	protected function createComponentRenameForm() {
		$dirName = (new \SplFileInfo($this->getAbsoluteDirectory()))->getFileName();
		$f = $this->createForm();
		$f->addHidden('dir', $dirName);
		$f->addText('name', '')
			->setDefaultValue($dirName);
		$f->addButtonSubmit('rename', '', 'ok');
		$f->addLinkSubmit('cancel', '', 'remove', $this->link('cancel!'));

		$f->autofocus('name');

		$f->addProtection();
		$f->enableBootstrap([
				'success'=>['rename'],
				'default'=>['cancel']
			], TRUE, 3, 'sm', 'form-inline');

		if($this->ajaxEnabled) {
			$f->enableAjax();
		}

		$f->onSuccess[] = [$this, 'renameFormSubmitted'];

		return $f;
	}

	/**
	 * @param Nette\Application\UI\Form $form
	 * @param                           $values
	 * @throws InvalidPathException
	 */
	public function renameFormSubmitted(Nette\Application\UI\Form $form, $values) {
		$newName = $values->dir;
		if($this->fileManager->isFeatureEnabled('renameDir') && $form->submitted === $form['rename']) {
			$newName = $values->name;
			if($values->dir != $newName) {
				$dir = $this->getAbsoluteDirectory();
				$newPath = Zax\Utils\PathHelpers::rename($dir, $newName);

				if(!Zax\Utils\PathHelpers::isSubdirOf($this->getRoot(), $newPath, TRUE)) {
					throw new InvalidPathException('Invalid name specified - ' . $newPath . ' is not inside ' . $this->getRoot());
				}
				Nette\Utils\FileSystem::rename($dir, $newPath);
				$this->onDirRename($dir, $newPath);
				$this->flashMessage('fileManager.alert.dirRenamed', 'success');
				$newPath = str_replace($this->root, '', $newPath);
				$this->fileManager->setDirectory($newPath);
				$this->fileManager->go('this', [
					'view' => 'Default',
					'dir' => $newPath,
					'directoryList-view' => 'Default'
				]);
			}
		}
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
	public function getRenameForm() {
		return $this['renameForm'];
	}

	public function viewDefault() {}

	public function beforeRender() {

	}

}