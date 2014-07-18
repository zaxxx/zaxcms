<?php

namespace ZaxCMS\Model;
use Zax,
	ZaxCMS,
	Nette,
	Kdyby;

/**
 * @method findAll()
 *
 */
abstract class Service extends Nette\Object {

	protected $em;

	protected $className;

	public function getDao() {
		return $this->em->getDao($this->className);
	}

	public function getRepository() {
		return $this->em->getRepository($this->className);
	}

	public function getEm() {
		return $this->em;
	}

}