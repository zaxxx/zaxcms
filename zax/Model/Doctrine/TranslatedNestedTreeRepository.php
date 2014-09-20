<?php

namespace Zax\Model\Doctrine;
use Zax,
	Nette,
	Kdyby,
	Gedmo,
	Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class TranslatedNestedTreeRepository extends NestedTreeRepository {

	protected $locale;

	public function setLocale($locale) {
		$this->locale = $locale;
		return $this;
	}

	public function childrenQuery($node = null, $direct = false, $sortByField = null, $direction = 'ASC', $includeNode = false) {
		if($this->locale === NULL) {
			throw new Zax\Model\LocaleNotSetException('Locale not set, use setLocale() method.');
		}
		$query = $this->childrenQueryBuilder($node, $direct, $sortByField, $direction, $includeNode)->getQuery();
		$query->setHint(
			\Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
			'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
		);
		$query->setHint(
			Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE,
			$this->locale
		);
		return $query;
	}

}