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
	protected $firstName;

	/**
	 * @ORM\Column(type="string", length=127)
	 */
	protected $surname;

	/**
	 * @Gedmo\Slug(fields={"firstName", "surname"})
	 * @ORM\Column(name="slug", type="string", length=127, unique=TRUE)
	 */
	protected $slug;

	/**
	 * @ORM\Column(type="string", length=512, nullable=TRUE)
	 */
	protected $image;

	/**
	 * @Gedmo\Translatable
	 * @ORM\Column(type="text", nullable=TRUE)
	 */
	protected $perex;

	/**
	 * @Gedmo\Translatable
	 * @ORM\Column(type="text", nullable=TRUE)
	 */
	protected $aboutAuthor;

	/**
	 * @Gedmo\Translatable
	 * @ORM\Column(type="text", nullable=TRUE)
	 */
	protected $sidebarContent;

	/**
	 * @ORM\ManyToMany(targetEntity="Article", mappedBy="authors")
	 * @ORM\JoinTable(name="author_article")
	 * @ORM\OrderBy({"id" = "DESC"})
	 */
	protected $articles;

	public function getName() {
		return $this->firstName . ' ' . $this->surname;
	}

}
