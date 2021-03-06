<?php

namespace ZaxCMS\Model\CMS\Entity;
use Zax,
	ZaxCMS,
	Nette,
	Kdyby,
	Kdyby\Doctrine\Entities\BaseEntity,
	Doctrine,
	Gedmo\Translatable\Translatable,
	Gedmo\Mapping\Annotation as Gedmo,
	Doctrine\ORM\Mapping as ORM;

/**
 * Project-specific category extension
 *
 * @Gedmo\Tree(type="materializedPath")
 * @ORM\Entity(repositoryClass="ZaxCMS\Model\CMS\Repository\CategoryTreeRepository")
 */
class Category extends BaseCategory {



}