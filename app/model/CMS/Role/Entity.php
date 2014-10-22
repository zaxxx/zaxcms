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
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass="ZaxCMS\Model\CMS\Repository\RoleTreeRepository")
 *
 * @property-read int $id
 * @property string $name
 * @property string $displayName
 * @property string $description
 * @property Role|NULL $parent
 * @property Role[]|NULL $children
 */
class Role extends BaseEntity {

	use Zax\Model\Doctrine\TNestedSet;

	const GUEST_ROLE = 0,
			USER_ROLE = 1,
			ADMIN_ROLE = 2;

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=63, unique=TRUE)
	 */
	protected $name;

	/**
	 * @Gedmo\Translatable
	 * @ORM\Column(type="string", length=63)
	 */
	protected $displayName;

	/**
	 * @Gedmo\Translatable
	 * @ORM\Column(type="string", length=255, nullable=TRUE)
	 */
	protected $description;

	/**
	 * @ORM\Column(type="integer", length=1, nullable=TRUE)
	 */
	protected $special;

	/**
	 * @Gedmo\TreeParent
	 * @ORM\ManyToOne(targetEntity="Role", inversedBy="children")
	 * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $parent;

	/**
	 * @ORM\OneToMany(targetEntity="Role", mappedBy="parent")
	 */
	protected $children;

	/**
	 * @ORM\OneToMany(targetEntity="User", mappedBy="role")
	 */
	protected $users;

	public function isGuestRole() {
		return $this->special === self::GUEST_ROLE;
	}

	public function isUserRole() {
		return $this->special === self::USER_ROLE;
	}

	public function isAdminRole() {
		return $this->special === self::ADMIN_ROLE;
	}

	public function canBeDeleted() {
		return $this->special === NULL;
	}

	public function hasReadOnlyPermissions() {
		return $this->isAdminRole();
	}

	public function canInherit() {
		return !$this->isGuestRole();
	}

	public function canBeInheritedFrom() {
		return !$this->isAdminRole();
	}

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