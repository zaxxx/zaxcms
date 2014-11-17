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
 * @property Category $category
 * @property string $title
 * @property string|NULL $perex
 * @property string|NULL $content
 * @property string $slug
 * @property \DateTime $createdAt
 * @property \DateTime $updatedAt
 * @property User $author
 * @property bool $isPublic
 * @property Tag[] $tags
 */
abstract class BaseArticle extends BaseEntity {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Category")
	 * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
	 */
	protected $category;

	/**
	 * @Gedmo\Translatable
	 * @ORM\Column(type="string", length=255)
	 */
	protected $title;

	/**
	 * @Gedmo\Translatable
	 * @ORM\Column(type="text", nullable=TRUE)
	 */
	protected $perex;

	/**
	 * @Gedmo\Translatable
	 * @ORM\Column(type="text", nullable=TRUE)
	 */
	protected $content;

	/**
	 * @Gedmo\Slug(fields={"title"})
	 * @ORM\Column(name="slug", type="string", length=255, unique=TRUE)
	 */
	protected $slug;

	/**
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(type="datetime")
	 */
	protected $createdAt;

	/**
	 * @Gedmo\Timestampable(on="update")
	 * @ORM\Column(type="datetime")
	 */
	protected $updatedAt;

	/*
	 * @ORM\ManyToOne(targetEntity="Author")
	 * @ORM\JoinColumn(name="author_id", referencedColumnName="id", nullable=TRUE)
	 *
	protected $author;*/

	/**
	 * @ORM\ManyToMany(targetEntity="Author", inversedBy="articles")
	 * @ORM\JoinTable(name="author_article")
	 * @ORM\OrderBy({"id" = "DESC"})
	 */
	protected $authors;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $hideAuthors;

	/**
	 * @ORM\Column(type="string", length=512, nullable=TRUE)
	 */
	protected $image;

	/**
	 * @Orm\Column(type="array", nullable=TRUE)
	 */
	protected $imageConfig;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $isMain;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $isVisibleInRootCategory;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $isPublic;

	/**
	 * @ORM\ManyToMany(targetEntity="Tag", inversedBy="articles")
	 * @ORM\JoinTable(name="tag_article")
	 * @ORM\OrderBy({"id" = "DESC"})
	 */
	protected $tags;

	/**
	 * @Gedmo\Translatable
	 * @ORM\Column(type="text", nullable=TRUE)
	 */
	protected $sidebarContent;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $sidebarCategory;

	public function setImageConfig(array $config) {
		$this->imageConfig = $config;
		return $this;
	}

	public function getImageConfig($key = NULL) {
		return $key === NULL ? $this->imageConfig : $this->imageConfig[$key];
	}

	public function setTags($tags) {
		$this->tags = $tags;
	}

	public function setAuthors($authors) {
		$this->authors = $authors;
	}

	/**
	 * @Gedmo\Locale
	 */
	private $locale;

	public function setTranslatableLocale($locale) {
		$this->locale = $locale;
	}

}