<?php

namespace Zax\Components\FileManager;
use Zax,
    Nette,
    DevModule;

/**
 * Class RenameFileControl
 *
 * @property-read Zax\Application\UI\Form $renameForm
 * @property-read FileListControl $fileList
 *
 * @package Zax\Components\FileManager
 */
class RenameFileControl extends FileManipulationControl {

	/**
	 * @var array
	 */
	public $onFileRename = [];

	/**
	 * @return Zax\Application\UI\Form
	 */
	protected function createComponentRenameForm() {
        $extension = (new \SplFileInfo($this->file))->getExtension();
        
        $f = $this->createForm();
        $f->addHidden('file', $this->file);
        $f->addText('name', '')
                ->setDefaultValue(($this->fileManager->isFeatureEnabled('renameExtension') ? $this->file : str_replace('.' . $extension, '', $this->file)));
        if(!$this->fileManager->isFeatureEnabled('renameExtension')) {
            $f->addStatic('extension', '')
                    ->setDefaultValue('.'.$extension);
        }
        $f->addButtonSubmit('rename', '', 'ok');
        $f->addButtonSubmit('cancel', '', 'remove');

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
        if($form->submitted === $form['rename']) {
            $newName = $values->name . ($this->fileManager->isFeatureEnabled('renameExtension') ? '' : '.' . (new \SplFileInfo($values->file))->getExtension());
            if($this->fileManager->isFeatureEnabled('renameFile') && $values->file != $newName) {
                $old = self::getPath($this, $values->file);
                $new = self::getPath($this, $newName);
                if(!Zax\Utils\PathHelpers::isSubdirOf($this->getRoot(), $new, TRUE)) {
                    throw new InvalidPathException('Invalid name specified.');
                }
                Nette\Utils\FileSystem::rename($old, $new);
                $this->onFileRename($old, $new);
                $this->flashMessage('fileManager.alert.fileRenamed', 'success');
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