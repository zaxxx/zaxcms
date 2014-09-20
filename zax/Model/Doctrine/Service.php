<?php

namespace Zax\Model\Doctrine;
use Zax,
	Kdyby,
	Nette;

/**
 * @method Kdyby\Doctrine\QueryBuilder createQueryBuilder()
 * @method \Doctrine\ORM\Query createQuery()
 */
abstract class Service extends Nette\Object implements IDoctrineService {

	protected $entityClassName;

	protected $entityManager;

	public function __construct(Kdyby\Doctrine\EntityManager $entityManager) {
		$this->entityManager = $entityManager;
	}

	public function getEntityManager() {
		return $this->entityManager;
	}

	public function getRepository() {
		if($this->entityClassName === NULL) {
			throw new Zax\Model\MissingEntityClassNameException;
		}
		return $this->entityManager->getRepository($this->entityClassName);
	}

	public function create() {
		if($this->entityClassName === NULL) {
			throw new Zax\Model\MissingEntityClassNameException;
		}
		return new $this->entityClassName;
	}

	public function findAll($orderBy = NULL, $limit = NULL, $offset = NULL) {
		return $this->getRepository()->findBy([], $orderBy = NULL, $limit = NULL, $offset = NULL);
	}

	public function findBy($criteria, $orderBy = NULL, $limit = NULL, $offset = NULL) {
		return $this->getRepository()->findBy($criteria, $orderBy, $limit, $offset);
	}

	public function getBy($criteria, $orderBy = NULL) {
		return $this->getRepository()->findOneBy($criteria, $orderBy);
	}

	public function get($id) {
		return $this->getRepository()->find($id);
	}

	public function countBy($criteria = []) {
		return $this->getRepository()->countBy($criteria);
	}

	public function findPairs($key = NULL, $value = NULL, $criteria = [], $orderBy = []) {
		return $this->getRepository()->findPairs($criteria, $value, $orderBy, $key);
	}

	public function findAssoc($key = NULL, $criteria = []) {
		return $this->getRepository()->findAssoc($criteria, $key);
	}

	public function persist($entity) {
		return $this->entityManager->persist($entity);
	}

	public function remove($entity) {
		return $this->entityManager->remove($entity);
	}

	public function flush() {
		return $this->entityManager->flush();
	}

	public function refresh($entity) {
		$this->entityManager->refresh($entity);
	}

	public function fetchQueryObject(Kdyby\Doctrine\QueryObject $queryObject) {
		return $this->getRepository()->fetch($queryObject);
	}

	public function __call($method, $args = []) {
		return call_user_func_array([$this->entityManager, $method], $args);
	}

} 