<?php

namespace Zax\Model;
use Zax,
	Nette;

interface IService {

	public function findBy($criteria, $orderBy = NULL, $limit = NULL, $offset = NULL);

	public function getBy($criteria, $orderBy = NULL);

	public function persist($entity);

	public function remove($entity);

	public function flush();

}
