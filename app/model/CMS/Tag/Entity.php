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
 * @ORM\Entity
 *
 * @property-read int $id
 */
class Tag extends BaseEntity {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\ManyToMany(targetEntity="Article", mappedBy="tags")
	 */
	protected $articles;

	/**
	 * @Gedmo\Translatable
	 * @ORM\Column(type="string", length=255)
	 */
	protected $title;

	/**
	 * @Gedmo\Slug(fields={"title"})
	 * @ORM\Column(name="slug", type="string", length=255, unique=TRUE)
	 */
	protected $slug;

	/**
	 * @Gedmo\Locale
	 */
	private $locale;

	public function setTranslatableLocale($locale) {
		$this->locale = $locale;
	}

}
