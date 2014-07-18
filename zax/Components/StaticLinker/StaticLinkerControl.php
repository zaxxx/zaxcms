<?php

namespace Zax\Components\StaticLinker;
use Nette,
    Zax,
    Zax\Application\UI\Control;

/**
 * Class StaticLinkerControl
 *
 * This component generates minified single-file versions of provided CSS and JS files.
 *
 * @property-read CssLinkerControl $cssLinker
 * @property-read JsLinkerControl $jsLinker
 * @method addCssFile($file)
 * @method addCssFiles($files)
 * @method addJsFile($file)
 * @method addJsFiles($files)
 *
 * @package Zax\Components\StaticLinker
 */
class StaticLinkerControl extends Control {

	/**
	 * @var array
	 */
	protected $linkerFactories = [];

	/**
	 * @var string
	 */
	protected $outputDir;

	/**
	 * @param ICssLinkerFactory $cssLinkerFactory
	 * @param IJsLinkerFactory  $jsLinkerFactory
	 */
	public function __construct(
        ICssLinkerFactory $cssLinkerFactory,
        IJsLinkerFactory $jsLinkerFactory
    ) {
        $this->registerLinker('css', $cssLinkerFactory);
        $this->registerLinker('js', $jsLinkerFactory);
    }

    public function viewDefault() {
        
    }
    
    public function beforeRender() {
        $this->template->linkers = array_keys($this->linkerFactories);
    }

	/**
	 * @param $root
	 * @return $this
	 */
	public function setRoot($root) {
        foreach(array_keys($this->linkerFactories) as $extension) {
            $this->getLinker($extension)->setRoot($root);
        }
        return $this;
    }

	/**
	 * @param $namespace
	 * @return $this
	 */
	public function setCacheNamespace($namespace) {
        foreach(array_keys($this->linkerFactories) as $extension) {
            $this->getLinker($extension)->setCacheNamespace($namespace);
        }
        return $this;
    }

    public function __call($name, $args = []) {
        $pos1 = strpos($name, 'add');
        $pos2 = strrpos($name, 'File');
        $pl = strrpos($name, 'Files') === $pos2;
        if($pos1 === 0 && $pos2 > 0) {
            $extension = strtolower(substr($name, 3, $pos2-3));
            if($pl) {
                foreach($args[0] as $file) {
                    $this->getLinker($extension)->addFile($file);
                }
            } else {
                $this->getLinker($extension)->addFile($args[0]);
            }
            return $this;
        }
        return parent::__call($name, $args);
    }

	/**
	 * @param $directory
	 * @return $this
	 */
	public function setOutputDirectory($directory) {
        $this->outputDir = $directory;
        foreach(array_keys($this->linkerFactories) as $extension) {
            $this->getLinker($extension)->setOutputDirectory($directory);
        }
        return $this;
    }

	/**
	 * @param IMinifier $minifier
	 * @return $this
	 */
	public function setMinifier(IMinifier $minifier) {
		foreach(array_keys($this->linkerFactories) as $extension) {
			$this->getLinker($extension)->setMinifier($minifier);
		}
		return $this;
	}

	/**
	 * @param $extension
	 * @return mixed
	 */
	public function getLinker($extension) {
        return $this[strtolower($extension) . 'Linker'];
    }

	/**
	 * @param                $extension
	 * @param ILinkerFactory $factory
	 * @return $this
	 */
	public function registerLinker($extension, ILinkerFactory $factory) {
        $this->linkerFactories[strtolower($extension)] = $factory;
        return $this;
    }

	/**
	 * @param $extension
	 * @return bool
	 */
	public function isLinkerRegistered($extension) {
        return array_key_exists(strtolower($extension), $this->linkerFactories);
    }

	/**
	 * @param $name
	 * @return Nette\ComponentModel\IComponent
	 */
	protected function createComponent($name) {
        if(strrpos($name, 'Linker') === strlen($name)-6) {
            $extension = strtolower(str_replace('Linker', '', $name));
            if($this->isLinkerRegistered($extension)) {
                return $this->linkerFactories[$extension]->create();
            }
        }
        return parent::createComponent($name);
    }

}