<?php

namespace ZaxCMS\Model\CMS\Service;
use Zax,
	ZaxCMS,
	ZaxCMS\Model\CMS\Entity,
	Nette,
	Kdyby,
	Gedmo;


class TagService extends Zax\Model\Doctrine\Service {

	public function __construct(Kdyby\Doctrine\EntityManager $entityManager) {
		parent::__construct($entityManager);
		$this->entityClassName = Entity\Tag::getClassName();
	}

	public function getOrCreateTag($title) {
		$tag = $this->getBy(['title' => $title]);
		if($tag === NULL) {
			$tag = $this->create();
			$tag->title = $title;
			$tag->aboutTag = '#### ' . $title;
			$this->persist($tag);
			$this->flush();
		}
		return $tag;
	}

}


trait TInjectTagService {

	/** @var TagService */
	protected $tagService;

	public function injectTagService(TagService $tagService) {
		$this->tagService = $tagService;
	}

}

