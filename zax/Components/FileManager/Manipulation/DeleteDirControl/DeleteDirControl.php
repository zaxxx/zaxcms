<?php

namespace Zax\Components\FileManager;
use Zax,
    Nette,
    DevModule;

/**
 * Class DeleteDirControl
 *
 * @property-read Zax\Application\UI\Form $deleteForm
 * @property-read DirectoryListControl $directoryList
 *
 * @package Zax\Components\FileManager
 */
class DeleteDirControl extends FileManipulationControl {

	/**
	 * @var array
	 */
	public $onDirDelete = [];

	/**
	 * @var array
	 */
	public $onDirTruncate = [];


	/**
	 * @return Zax\Application\UI\Form
	 */
	protected function createComponentDeleteForm() {
        $dirName = (new \SplFileInfo($this->getAbsoluteDirectory()))->getFileName();

        $f = $this->createForm();
        $f->addHidden('dir', $dirName);
        $f->addButtonSubmit('delete', 'common.button.delete', 'trash');
        if($this->fileManager->isFeatureEnabled('truncateDir')) {
            $f->addButtonSubmit('truncate', 'fileManager.button.truncate');
        }
        $f->addButtonSubmit('cancel', '', 'remove');
        $f->addProtection();
        $f->enableBootstrap(
            [
                'danger'=>['delete', 'truncate'],
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
        $goToDir = $this->getDirectory();
        $name = $this->getAbsoluteDirectory();
        if($this->fileManager->isFeatureEnabled('deleteDir') && $form->submitted === $form['delete']) {
            Nette\Utils\FileSystem::delete($this->getAbsoluteDirectory());
            $goToDir = Zax\Utils\PathHelpers::getParentDir($this->getDirectory());
            $this->onDirDelete($name);
            $this->flashMessage('fileManager.alert.dirDeleted', 'success');
        } else if($this->fileManager->isFeatureEnabled('truncateDir') && $form->submitted === $form['truncate']) {
            foreach(Nette\Utils\Finder::find('*')->in($this->getAbsoluteDirectory()) as $k => $file) {
                Nette\Utils\FileSystem::delete($k);
            }
            $this->onDirTruncate($name);
            $this->flashMessage('fileManager.alert.dirTruncated', 'success');
        }
        $this->fileManager->setDirectory($goToDir);
        $this->fileManager->go('this', ['dir' => $goToDir, 'view' => 'Default', 'directoryList-view' => 'Default']);
    }

	/**
	 * @return Zax\Components\FileManager\DirectoryListControl
	 */
	public function getDirectoryList() {
        return $this->lookup('Zax\Components\FileManager\DirectoryListControl');
    }

	/**
	 * @return Zax\Application\UI\Form
	 */
	public function getDeleteForm() {
        return $this['deleteForm'];
    }
    
    public function viewDefault() {}
    
    public function beforeRender() {
        $this->template->dir = (new \SplFileInfo($this->getAbsoluteDirectory()))->getFileName();
    }
    
}