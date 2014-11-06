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
 * @property-read int $id
 * @property string $name
 * @property string $slug
 */
abstract class BaseAuthor extends BaseEntity {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=127)
	 */
	protected $name;

	/**
	 * @Gedmo\Slug(fields={"name"})
	 * @ORM\Column(name="slug", type="string", length=127, unique=TRUE)
	 */
	protected $slug;

	/**
	 * @ORM\OneToMany(targetEntity="Article", mappedBy="author")
	 */
	protected $articles;

}
