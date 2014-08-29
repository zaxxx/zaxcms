<?php

namespace ZaxCMS\Model;
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
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass="Zax\Model\TranslatedNestedTreeRepository")
 *
 * @property-read int $id
 * @property string $name
 * @property string $description
 * @property string $text
 * @property string|NULL $html
 * @property string|NULL $nhref
 * @property array|NULL $nhrefParams
 * @property string|NULL $href
 * @property string|NULL $htmlClass
 * @property bool $isMenuItem
 * @property bool $secured
 * @property Permission[] $permissions
 * @property Menu|NULL $parent
 */
class Menu extends BaseEntity implements Translatable {

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
	 * @ORM\Column(type="string", length=255)
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
	 * @Gedmo\TreeLeft
	 * @ORM\Column(type="integer")
	 */
	protected $lft;

	/**
	 * @Gedmo\TreeRight
	 * @ORM\Column(type="integer")
	 */
	protected $rgt;

	/**
	 * @Gedmo\TreeLevel
	 * @ORM\Column(type="integer")
	 */
	protected $depth;

	/**
	 * @Gedmo\TreeRoot
	 * @ORM\Column(type="integer", nullable=TRUE)
	 */
	protected $root;

	/**
	 * @Gedmo\TreeParent
	 * @ORM\ManyToOne(targetEntity="ZaxCMS\Model\Menu", inversedBy="children")
	 * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $parent;

	/**
	 * @ORM\OneToMany(targetEntity="ZaxCMS\Model\Menu", mappedBy="parent")
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

}