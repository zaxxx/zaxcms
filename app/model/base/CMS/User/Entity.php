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
 * @property-read $id
 * @property string $name
 * @property string $email
 * @property Role $role
 * @property UserLogin $login
 */
abstract class BaseUser extends BaseEntity {

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
	 * @ORM\ManyToOne(targetEntity="Role", inversedBy="users")
	 * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
	 */
	protected $role;

	/**
	 * @ORM\OneToOne(targetEntity="UserLogin")
	 * @ORM\JoinColumn(name="login_id", referencedColumnName="id")
	 */
	protected $login;

}