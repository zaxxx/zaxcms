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
 * @property Category|NULL $parent
 * @property Category[]|NULL $children
 * @property string $title
 * @property string $slug
 * @property Article[] $articles
 */
abstract class BaseCategory extends BaseEntity {

	use Zax\Model\Doctrine\TMaterializedPath;

	/**
	 * @Gedmo\TreeParent
	 * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
	 * })
	 */
	protected $parent;

	/**
	 * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
	 * @ORM\OrderBy({"id" = "ASC"})
	 */
	protected $children;

	/**
	 * @Gedmo\Translatable
	 * @ORM\Column(type="string", length=255)
	 */
	protected $title;

	/**
	 * @Gedmo\Slug(handlers={
	 *      @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\TreeSlugHandler", options={
	 *          @Gedmo\SlugHandlerOption(name="parentRelationField", value="parent"),
	 *          @Gedmo\SlugHandlerOption(name="separator", value="/")
	 *      })
	 * }, fields={"title"})
	 * @ORM\Column(name="slug", type="string", length=255, unique=TRUE)
	 */
	protected $slug;

	/**
	 * @ORM\OneToMany(targetEntity="Article", mappedBy="category")
	 * @ORM\OrderBy({"id" = "DESC"})
	 */
	protected $articles;

	/**
	 * @Gedmo\Locale
	 */
	private $locale;

	public function setTranslatableLocale($locale) {
		$this->locale = $locale;
	}

}