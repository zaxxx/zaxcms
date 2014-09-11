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
 * @property Resource $resource
 * @property Privilege $privilege
 */
class Permission extends BaseEntity {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Resource")
	 * @ORM\JoinColumn(name="resource_id", referencedColumnName="id")
	 */
	protected $resource;

	/**
	 * @ORM\ManyToOne(targetEntity="Privilege")
	 * @ORM\JoinColumn(name="privilege_id", referencedColumnName="id")
	 */
	protected $privilege;

	/**
	 * @Gedmo\Translatable
	 * @ORM\Column(type="string", length=255, nullable=TRUE)
	 */
	protected $note;

	/**
	 * @Gedmo\Locale
	 */
	private $locale;

	public function setTranslatableLocale($locale) {
		$this->locale = $locale;
	}

	public function getLocale() {
		return $this->locale;
	}

}