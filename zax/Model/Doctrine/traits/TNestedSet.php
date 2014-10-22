<?php

namespace Zax\Model\Doctrine;
use Zax,
	Kdyby,
	Doctrine\ORM\Mapping as ORM,
	Gedmo\Mapping\Annotation as Gedmo,
	Nette;

trait TNestedSet {

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

}