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
 * Project-specific menu extension
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass="ZaxCMS\Model\CMS\Repository\MenuTreeRepository")
 */
class Menu extends BaseMenu {



}