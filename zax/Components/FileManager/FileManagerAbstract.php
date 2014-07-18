<?php

namespace Zax\Components\FileManager;
use Zax,
    Nette,
    DevModule;

/**
 * Class FileManagerAbstract
 *
 * @property string $root
 * @property string $directory
 * @property-read string $absoluteDirectory
 * @property-read FileManagerControl $fileManager
 *
 * @package Zax\Components\FileManager
 */
abstract class FileManagerAbstract extends Zax\Application\UI\SecuredControl implements IFilesystemContextAware {

	/**
	 * @var array
	 */
	public $onUpdate = [];

	/**
	 * @var string
	 */
	protected $root;

	/**
	 * @var string
	 */
	protected $dir;

	/**
	 * @var
	 */
	protected $subdirs;

	/**
	 * @param string $root
	 * @return $this
	 */
	public function setRoot($root) {
	    if(!file_exists($root)) {
		    Nette\Utils\FileSystem::createDir($root);
	    }
        $this->root = realpath($root);
        return $this;
    }

	/**
	 * @return string
	 * @throws RootNotSetException
	 */
	public function getRoot() {
        if($this->root === NULL) {
            throw new RootNotSetException('Root path not set, use "setRoot" method');
        }
        return $this->root;
    }

	/**
	 * @param string $directory
	 * @return $this
	 */
	public function setDirectory($directory) {
        $this->dir = $directory;
        foreach($this->getComponents(TRUE, 'Zax\Components\FileManager\IFilesystemContextAware') as $component) {
            $component->setDirectory($directory);
        }
        return $this;
    }

	/**
	 * @return string
	 */
	public function getDirectory() {
        return $this->dir;
    }

	/**
	 * @return string
	 * @throws InvalidPathException
	 */
	public function getAbsoluteDirectory() {
        $dir = realpath($this->getRoot() . $this->getDirectory());

        if(!Zax\Utils\PathHelpers::isSubdirOf($this->getRoot(), $dir)) {
            throw new InvalidPathException('Outside of allowed folder - ' . $dir . ' is not inside ' . $this->getRoot());
        }
        return $dir;
    }

	/**
	 * @return Nette\Utils\Finder
	 */
	public function getSubdirectories() {
        if($this->subdirs === NULL) {
            $this->subdirs = Nette\Utils\Finder::findDirectories('*')->from($this->getRoot());
        }
        return $this->subdirs;
    }

	/**
	 * @param string $rootName
	 * @return array
	 */
	public function getSelectOptions($rootName = '(Kořen)') {
        $options = [
            $this->getRoot() => $rootName
        ];
        foreach($this->getSubdirectories() as $key => $dir) {
            $options[$key] = str_replace($this->getRoot(), '', $dir);
        }
        return $options;
    }

	/**
	 * @param $presenter
	 */
	public function attached($presenter) {
        parent::attached($presenter);
        $this->onUpdate($this);
        try {
            $this->getAbsoluteDirectory();
        } catch (InvalidPathException $ex) {
            $fm =$this->getFileManager();
            $fm->flashMessage('fileManager.alert.pathNotFound', 'danger');
            $fm->setDirectory(NULL)
                ->redirect('this', ['view'=>'Default','directoryList-view'=>'Default','fileList-view'=>'Default']);
        }
    }

	/**
	 * @return Zax\Components\FileManager\FileManagerControl
	 */
	public function getFileManager() {
        return $this->lookup('Zax\Components\FileManager\FileManagerControl');
    }

	/**
	 * @param $name
	 * @return Nette\ComponentModel\IComponent
	 */
	protected function createComponent($name) {
        $control = parent::createComponent($name);

        if($control instanceof IFilesystemContextAware) {
            $control->setRoot(realpath($this->getRoot()));
            $control->setDirectory($this->getDirectory());
        }

        if($this->ajaxEnabled && $control instanceof Zax\Application\UI\Control) {
            $control->enableAjax();
        }

        return $control;
    }

	/**
	 * @param IFileSystemContextAware $sender
	 * @param                         $fileOrDir
	 * @return string
	 */
	protected static function getPath(IFileSystemContextAware $sender, $fileOrDir) {
        return $sender->getAbsoluteDirectory() . '/' . Nette\Utils\Strings::webalize($fileOrDir, '._@', FALSE);
    }

}