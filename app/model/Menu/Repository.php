<?php

namespace ZaxCMS\Model;
use Zax,
	ZaxCMS,
	Nette,
	Kdyby,
	Gedmo,
	Doctrine,
	Doctrine\ORM\EntityRepository,
	Gedmo\Tree\Entity\Repository\NestedTreeRepository;

// TODO: WTF...
class MenuRepository extends NestedTreeRepository {

	public function getNodesHierarchyQuery($node = null, $direct = false, array $options = array(), $includeNode = false) {
		$query = $this->getNodesHierarchyQueryBuilder($node, $direct, $options, $includeNode)->getQuery();
		dump('happened'); // sadly.. not
		$query->setHint(
			Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
			'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
		);
		return $query;
	}

}