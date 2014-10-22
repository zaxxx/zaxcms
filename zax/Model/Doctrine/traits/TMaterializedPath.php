<?php

namespace Zax\Model\Doctrine;
use Zax,
	Kdyby,
	Doctrine\ORM\Mapping as ORM,
	Gedmo\Mapping\Annotation as Gedmo,
	Nette;

trait TMaterializedPath {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @Gedmo\TreePathSource
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @Gedmo\TreePath(endsWithSeparator=FALSE, separator="/")
	 * @ORM\Column(type="string", length=255, nullable=TRUE)
	 */
	protected $path;

	/**
	 * @Gedmo\TreeLevel
	 * @ORM\Column(type="integer", nullable=TRUE)
	 */
	protected $depth;

}