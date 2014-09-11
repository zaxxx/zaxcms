<?php

namespace Zax\Application;
use Zax,
	Nette;

class PresenterFactory extends Nette\Application\PresenterFactory {

	protected $container;

	protected $baseDir;

	public function __construct($baseDir, Nette\DI\Container $container) {
		$this->baseDir = $baseDir;
		$this->container = $container;
	}

	/* Hopefully this is just temporary..
	 * https://github.com/nette/application/commit/a0333bd1761f4e9539a123a535fbe4383a59c43c
	 * */
	public function createPresenter($name) {
		$class = $this->getPresenterClass($name);
		if(count($services = $this->container->findByType($class)) === 1) {
			$presenter = $this->container->createService($services[0]);
		} else {
			$presenter = $this->container->createInstance($class);
			$this->container->callInjects($presenter);
		}

		if($presenter instanceof Nette\Application\UI\Presenter && $presenter->invalidLinkMode === NULL) {
			$presenter->invalidLinkMode = $this->container->parameters['debugMode'] ? UI\Presenter::INVALID_LINK_WARNING : UI\Presenter::INVALID_LINK_SILENT;
		}

		return $presenter;
	}

}