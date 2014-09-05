<?php

namespace Zax\Components\Collection;
use Nette,
	Kdyby,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

abstract class CollectionControl extends SecuredControl {

	/** @return Kdyby\Doctrine\ResultSet */
	abstract protected function getResultSet();

}