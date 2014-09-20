<?php

namespace ZaxCMS\Components\Navigation\MarkActiveStrategies;
use Zax,
	ZaxCMS,
	Nette;

abstract class Strategy extends Nette\Object {

	protected $application;

	protected $request;

	public function __construct(Nette\Application\Application $application) {
		$this->application = $application;
		$this->request = $application->createInitialRequest();
	}

	abstract public function isActive(ZaxCMS\Model\CMS\Entity\Menu $menuItem);

}