<?php

namespace ZaxCMS\Model\CMS\Query;
use Zax,
	ZaxCMS\Model,
	Nette,
	Kdyby,
	Gedmo,
	Doctrine;

class PermissionQuery extends Kdyby\Doctrine\QueryObject {

	protected $locale;

	public function __construct($locale) {
		$this->locale = $locale;
	}

	protected function doCreateQuery(Kdyby\Persistence\Queryable $repository) {
		$query =  $repository->createQueryBuilder()
			->select('perm', 'res', 'priv')
			->from(Model\CMS\Entity\Permission::getClassName(), 'perm')
			->join('perm.resource', 'res')
			->join('perm.privilege', 'priv')
			->orderBy('res.id, priv.id')
			->getQuery()
			->useResultCache(TRUE, NULL, Model\CMS\AclFactory::CACHE_TAG);
		$query->setHint(
			Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
			'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
		);
		$query->setHint(
			Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE,
			$this->locale
		);
		return $query;
	}

}