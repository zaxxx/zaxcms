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

	protected $locale;

	public function setLocale($locale) {
		$this->locale = $locale;
		return $this;
	}

	public function childrenQuery($node = null, $direct = false, $sortByField = null, $direction = 'ASC', $includeNode = false) {
		if($this->locale === NULL) {
			throw new \Exception('Locale not set'); // TODO
		}
		$query = $this->childrenQueryBuilder($node, $direct, $sortByField, $direction, $includeNode)->getQuery();
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