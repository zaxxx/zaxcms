<?php

namespace ZaxCMS\Model\CMS\Query;
use Zax,
	ZaxCMS\Model,
	Nette,
	Kdyby,
	Gedmo,
	Doctrine;

class ArticleQuery extends Zax\Model\Doctrine\QueryObject {

	protected $locale;

	public function __construct($locale = NULL) {
		$this->locale = $locale;
	}

	public function inCategories($categories) {
		if($categories === NULL) {
			return $this;
		}

		$ids = [];
		foreach($categories as $category) {
			$ids[] = $category->id;
		}

		$this->filter[] = function(Kdyby\Doctrine\QueryBuilder $qb) use ($categories) {
			$qb->andWhere('a.category IN (:categories)')->setParameter('categories', $categories);
		};
		return $this;
	}

	public function withTag(Model\CMS\Entity\Tag $tag = NULL) {
		if($tag === NULL) {
			return $this;
		}
		$this->filter[] = function(Kdyby\Doctrine\QueryBuilder $qb) use ($tag) {
			$qb->innerJoin('a.tags', 'd')
				->andWhere('d.id IN (:tag)')->setParameter('tag', $tag->id);
		};
		return $this;
	}

	public function search($needle) {
		$this->filter[] = function(Kdyby\Doctrine\QueryBuilder $qb) use ($needle) {

			$qb->andWhere('a.title LIKE :search')
				->setParameter('search', "%$needle%");
		};
		return $this;
	}

	protected function doCreateQuery(Kdyby\Persistence\Queryable $repository) {
		$qb = $repository->createQueryBuilder()
			->select('a, b, c')
			->from(Model\CMS\Entity\Article::getClassName(), 'a')
			->innerJoin('a.category', 'b')
			->leftJoin('a.tags', 'c')
			->orderBy('a.id', 'DESC');
		$this->applyFilters($qb);
		$query = $qb->getQuery()
			;//->useResultCache(TRUE, NULL, Model\CMS\AclFactory::CACHE_TAG);
		/*if($this->locale !== NULL) {
			$query->setHint(
				Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE,
				$this->locale
			);
		}*/
		return $query;
	}

}