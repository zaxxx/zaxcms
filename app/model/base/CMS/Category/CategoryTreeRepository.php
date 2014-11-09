<?php

namespace ZaxCMS\Model\CMS\Repository;
use Gedmo\Tree\Entity\Repository\MaterializedPathRepository;
use Zax,
	ZaxCMS,
	Nette;

class CategoryTreeRepository extends MaterializedPathRepository {

	public function getChildrenQuery($node = null, $direct = false, $sortByField = null, $direction = 'ASC', $includeNode = false) {
		$result = parent::getChildrenQuery($node, $direct, $sortByField, $direction, $includeNode);
		$result->useResultCache(TRUE, NULL, ZaxCMS\Model\CMS\Service\CategoryService::CACHE_TAG);
		return $result;
	}

}