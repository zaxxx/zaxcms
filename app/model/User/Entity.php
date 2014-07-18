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
 * @ORM\Entity
 */
class User extends BaseEntity {

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
	 * @ORM\Column(type="string", length=127, unique=TRUE)
	 */
	protected $email;

	/**
	 * @ORM\ManyToOne(targetEntity="Role")
	 * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
	 */
	protected $role;

}