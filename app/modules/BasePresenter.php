<?php

namespace ZaxCMS;
use Nette,
	Zax,
	ZaxCMS,
	ZaxCMS\Components,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Kdyby;


abstract class BasePresenter extends ZaxUI\Presenter {

	protected $webContentFactory;

	protected $menuWrapperFactory;

	public function injectWebContentFactory(Components\WebContent\IWebContentFactory $webContentFactory) {
		$this->webContentFactory = $webContentFactory;
	}

	public function injectMenuWrapperFactory(Components\Menu\IMenuWrapperFactory $menuWrapperFactory) {
		$this->menuWrapperFactory = $menuWrapperFactory;
	}

	protected function createComponentWebContent() {
		return new NetteUI\Multiplier(function($name) {
			return $this->webContentFactory->create()
				->setCacheNamespace('ZaxCMS.WebContent.' . $name)
				->enableAjax(TRUE)
				->setName($name);
		});
	}

	protected function createComponentMenuWrapper() {
		return new NetteUI\Multiplier(function($name) {
			return $this->menuWrapperFactory->create()->setName($name)->enableAjax();
		});
	}

	/**
	 * @return Nette\Application\UI\Multiplier
	 */
	protected function createComponentStaticLinker() {
		return new Nette\Application\UI\Multiplier(function($id) {
			$dir = $this->rootDir . '/pub';
			return $this->staticLinkerFactory->create()
				->setCacheNamespace('Zax.StaticLinker.' . $id)
				->setRoot($this->rootDir)
				->setOutputDirectory($dir . '/combined/' . $id)
				->addCssFiles(Nette\Utils\Finder::findFiles('*.css')->from($dir . '/' . $id . '/css'))
				->addJsFiles(Nette\Utils\Finder::findFiles('*.js')->from($dir . '/' . $id . '/js'));
		});
	}





}