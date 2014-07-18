<?php

namespace Zax\Components\StaticLinker;
use Nette,
    Zax,
    Zax\Application\UI\Control;

abstract class StaticLinkerAbstract extends Control implements ILinker {

	use Zax\Traits\TCacheable;

	/**
	 * @var Nette\Http\Request
	 */
	protected $httpRequest;

	/**
	 * @var string
	 */
	protected $root;

	/**
	 * @var IMinifier
	 */
	protected $minifier;

	/**
	 * @var array
	 */
	protected $files = [];

	/**
	 * @var string
	 */
	protected $outputDir;

	/**
	 * @var string
	 */
	protected $processedFile;

	/**
	 * @return string
	 */
	abstract protected function getExtension();

	/**
	 * @param Nette\Http\Request $request
	 */
	public function injectPrimary(
        Nette\Http\Request $request
    ) {
        $this->httpRequest = $request;
    }

	/**
	 * @param $root
	 * @return $this
	 */
	public function setRoot($root) {
        $this->root = $root;
        return $this;
    }

	/**
	 * @return string
	 */
	public function getRoot() {
        return $this->root;
    }

	/**
	 * @param $file
	 * @return $this
	 */
	public function addFile($file) {
        $this->files[] = (string)$file;
        return $this;
    }

	/**
	 * @param $directory
	 * @return $this
	 */
	public function setOutputDirectory($directory) {
        $this->outputDir = $directory;
        if(!file_exists($this->outputDir)) {
            Nette\Utils\FileSystem::createDir($this->outputDir, 0766);
        }
        return $this;
    }

	/**
	 * @return string
	 */
	protected function combineFiles() {
        ob_start();
        foreach($this->files as $file) {
            echo file_get_contents($file);
        }
        $combined = ob_get_contents();
        ob_end_clean();
        return $combined;
    }

	/**
	 * @return string
	 */
	protected function makeFileName() {
        return 'c-' . substr(md5(implode('-', $this->files) . time()), 5, 5) . '.' . $this->getExtension();
    }

	/**
	 * @return string
	 */
	protected function getCacheKey() {
        return 'combinedName';
    }

	/**
	 * @return string
	 */
	protected function getBaseUrl() {
        return rtrim($this->httpRequest->getUrl()->getBaseUrl(), '/');
    }

	/**
	 * @return mixed
	 */
	protected function getBasePath() {
        return preg_replace('#https?://[^/]+#A', '', $this->getBaseUrl());
    }

	/**
	 * @return DefaultMinifier|IMinifier
	 */
	protected function getMinifier() {
		if($this->minifier === NULL) {
			$this->minifier = new DefaultMinifier;
		}
		return $this->minifier;
	}

	/**
	 * @param IMinifier $minifier
	 * @return $this
	 */
	public function setMinifier(IMinifier $minifier) {
		$this->minifier = $minifier;
		return $this;
	}

	/**
	 * @param $text
	 * @return string
	 */
	protected function minify($text) {
		return $this->getMinifier()->minify($text);
    }

	/**
	 * @return string
	 */
	public function process() {
        if($this->processedFile)
            return $this->processedFile;
        $name = $this->cache->load($this->getCacheKey());
        if($name === NULL) {
            $this->cache->save($this->getCacheKey(), $name = $this->makeFileName(), [
                Nette\Caching\Cache::FILES => $this->files
            ]);
        }
        $path = $this->outputDir . '/' . $name;
        if(!file_exists($path)) {
            foreach(Nette\Utils\Finder::findFiles('*.' . $this->getExtension())->in($this->outputDir) as $k => $file) {
                Nette\Utils\FileSystem::delete($k);
            }
            file_put_contents($path, $this->minify($this->combineFiles()));
        }
        return $this->processedFile = $path;
    }

    public function beforeRender() {
        $this->template->processedFile = Zax\Utils\PathHelpers::getPath($this->getBasePath(), $this->getRoot(), new \SplFileInfo($this->process()));
    }
}