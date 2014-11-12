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

	public function publicOnly($publicOnly = TRUE) {
		if(!$publicOnly) {
			return $this;
		}
		$this->filter[] = function(Kdyby\Doctrine\QueryBuilder $qb) {
			$qb->andWhere('a.isPublic = :public')->setParameter('public', TRUE);
		};
		return $this;
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
			$qb->innerJoin('a.tags', 'innerTags')
				->andWhere('innerTags.id IN (:tag)')->setParameter('tag', $tag->id);
		};
		return $this;
	}

	public function byAuthor(Model\CMS\Entity\Author $author = NULL) {
		if($author === NULL) {
			return $this;
		}
		$this->filter[] = function(Kdyby\Doctrine\QueryBuilder $qb) use ($author) {
			$qb->innerJoin('a.authors', 'innerAuthors')
				->andWhere('innerAuthors.id = :authors')->setParameter('authors', $author->id);
		};
		return $this;
	}

	public function search($needle = NULL, $titleOnly = FALSE) {
		if($needle === NULL) {
			return $this;
		}
		$this->filter[] = function(Kdyby\Doctrine\QueryBuilder $qb) use ($needle) {

			$qb->andWhere('a.title LIKE :search')
				->orWhere('a.perex LIKE :search')
				->orWhere('a.content LIKE :search')
				->setParameter('search', "%$needle%");

			/*if(!$titleOnly) {
				$qb->orWhere('a.perex LIKE :search');
				$qb->orWhere('a.content LIKE :search');
			}*/
		};
		return $this;
	}

	public function addRootCategoryFilter(Model\CMS\Entity\Category $category = NULL, $canEditArticles = FALSE) {
		if($category === NULL || $category->depth > 0) {
			return $this;
		}
		$this->filter[] = function(Kdyby\Doctrine\QueryBuilder $qb) use ($category, $canEditArticles) {
			if(!$canEditArticles) {
				$qb->andWhere('a.isVisibleInRootCategory = :showInRoot')->setParameter('showInRoot', TRUE);
			}
			$qb->addOrderBy('a.isMain', 'DESC');
		};
		return $this;
	}

	protected function doCreateQuery(Kdyby\Persistence\Queryable $repository) {
		$qb = $repository->createQueryBuilder()
			->select('a, b, c, d')
			->from(Model\CMS\Entity\Article::getClassName(), 'a')
			->leftJoin('a.category', 'b')
			->leftJoin('a.tags', 'c')
			->leftJoin('a.authors', 'd');

		$this->applyFilters($qb);

		$qb->addOrderBy('a.createdAt', 'DESC');


		$query = $qb->getQuery()
			->useResultCache(TRUE, NULL, Model\CMS\Service\ArticleService::CACHE_TAG);

		return $query;
	}

}