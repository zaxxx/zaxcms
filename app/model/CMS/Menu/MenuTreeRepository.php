<?php

namespace ZaxCMS\Model\CMS\Repository;
use Zax,
	ZaxCMS,
	Nette;

class MenuTreeRepository extends Zax\Model\TranslatedNestedTreeRepository {

	public function childrenQuery($node = null, $direct = false, $sortByField = null, $direction = 'ASC', $includeNode = false) {
		$result = parent::childrenQuery($node, $direct, $sortByField, $direction, $includeNode);
		$result->useResultCache(TRUE, NULL, ZaxCMS\Model\CMS\Service\MenuService::CACHE_TAG);
		return $result;
	}

}