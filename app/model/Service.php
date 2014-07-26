<?php

namespace ZaxCMS\Model;
use Zax,
	ZaxCMS,
	Nette,
	Kdyby;

abstract class Service extends Nette\Object implements Zax\Model\IService {

	protected $em;

	protected $className;

	/**
	 * @return Kdyby\Doctrine\EntityDao
	 */
	public function getDao() {
		return $this->em->getDao($this->className);
	}

	public function getRepository() {
		return $this->em->getRepository($this->className);
	}

	/**
	 * @return Kdyby\Doctrine\EntityManager
	 */
	public function getEm() {
		return $this->em;
	}

	public function findAll($orderBy = NULL, $limit = NULL, $offset = NULL) {
		return $this->getDao()->findBy([], $orderBy = NULL, $limit = NULL, $offset = NULL);
	}

	public function findBy($criteria, $orderBy = NULL, $limit = NULL, $offset = NULL) {
		return $this->getDao()->findBy($criteria, $orderBy, $limit, $offset);
	}

	public function getBy($criteria, $orderBy = NULL) {
		return $this->getDao()->findOneBy($criteria, $orderBy);
	}

	public function persist($entity) {
		return $this->getEm()->persist($entity);
	}

	public function remove($entity) {
		return $this->getEm()->remove($entity);
	}

	public function flush() {
		return $this->getEm()->flush();
	}

	public function refresh($entity) {
		$this->getEm()->refresh($entity);
	}

}