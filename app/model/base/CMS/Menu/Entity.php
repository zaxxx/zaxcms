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
	Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Cache\Cache as Cache;

/**
 * @property-read int $id
 * @property string $name
 * @property string $text
 * @property string|NULL $nhref
 * @property array|NULL $nhrefParams
 * @property string|NULL $href
 * @property string|NULL $htmlClass
 * @property string|NULL $htmlTarget
 * @property string|NULL $icon
 * @property string|NULL $title
 * @property bool $secured
 * @property Permission[] $permissions
 * @property Menu|NULL $parent
 * @property Menu[]|NULL $children
 * @property-read string|NULL $presenterName
 */
abstract class BaseMenu extends BaseEntity implements Translatable {

	use Zax\Model\Doctrine\TNestedSet;

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=63, unique=TRUE, nullable=TRUE)
	 */
	protected $name;

	/**
	 * @Gedmo\Translatable
	 * @ORM\Column(type="string", length=255, nullable=TRUE)
	 */
	protected $text;

	/**
	 * @ORM\Column(type="string", length=255, nullable=TRUE)
	 */
	protected $nhref;

	/**
	 * @ORM\Column(type="array", nullable=TRUE)
	 */
	protected $nhrefParams;

	/**
	 * @ORM\Column(type="string", length=511, nullable=TRUE)
	 */
	protected $href;

	/**
	 * @ORM\Column(type="string", length=255, nullable=TRUE)
	 */
	protected $htmlClass;

	/**
	 * @ORM\Column(type="string", length=63, nullable=TRUE)
	 */
	protected $htmlTarget;

	/**
	 * @ORM\Column(type="string", length=63, nullable=TRUE)
	 */
	protected $icon;

	/**
	 * @ORM\Column(type="string", length=511, nullable=TRUE)
	 */
	protected $title;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $secured;

	/**
	 * @ORM\ManyToMany(targetEntity="Permission")
	 */
	protected $permissions;

	/**
	 * @Gedmo\TreeParent
	 * @ORM\ManyToOne(targetEntity="Menu", inversedBy="children")
	 * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $parent;

	/**
	 * @ORM\OneToMany(targetEntity="Menu", mappedBy="parent")
	 * @ORM\OrderBy({"lft" = "ASC"})
	 */
	protected $children;

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

	public function getPresenterName() {
		if($this->nhref === NULL) {
			return NULL;
		}
		$split = explode(':', $this->nhref, -1);
		array_shift($split);
		return implode(':', $split);
	}

	public function getActionName() {
		$ex = explode(':', $this->nhref);
		return end($ex);
	}

}