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
 *
 * @property-read $id
 * @property string $password
 * @property string|NULL $passwordChangeHash
 * @property Nette\Utils\DateTime|NULL $passwordChangeAskedAt
 * @property Nette\Utils\DateTime $passwordLastChangedAt
 * @property bool $isBanned
 * @property string|NULL $verifyHash
 * @property-read bool $verified
 * @property Nette\Utils\DateTime $registeredAt
 * @property User $user
 */
class UserLogin extends BaseEntity {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=60)
	 */
	protected $password;

	/**
	 * @ORM\Column(type="string", length=15, nullable=TRUE)
	 */
	protected $passwordChangeHash;

	/**
	 * @ORM\Column(type="datetime", nullable=TRUE)
	 */
	protected $passwordChangeAskedAt;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $passwordLastChangedAt;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $isBanned;

	/**
	 * @ORM\Column(type="string", length=15, nullable=TRUE)
	 */
	protected $verifyHash;

	public function isVerified() {
		return $this->verifyHash === NULL;
	}

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $registeredAt;

	/**
	 * @ORM\OneToOne(targetEntity="User")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 */
	protected $user;

}