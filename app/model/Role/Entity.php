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
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 *
 * @property-read int $id
 * @property string $name
 * @property string $displayName
 * @property string $description
 * @property Role|NULL $parent
 */
class Role extends BaseEntity {

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
	 * @ORM\Column(type="string", length=63)
	 */
	protected $displayName;

	/**
	 * @ORM\Column(type="string", length=255, nullable=TRUE)
	 */
	protected $description;

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
	 * @ORM\ManyToOne(targetEntity="ZaxCMS\Model\Role", inversedBy="children")
	 * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $parent;

	/**
	 * @ORM\OneToMany(targetEntity="ZaxCMS\Model\Role", mappedBy="parent")
	 */
	protected $children;

}