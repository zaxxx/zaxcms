<?php

namespace ZaxCMS\Model;
use Zax,
	ZaxCMS,
	Nette,
	Kdyby,
	Gedmo;


class MenuService extends Service {

	public function __construct(Kdyby\Doctrine\EntityManager $em) {
		$this->em = $em;
		$this->className = Menu::getClassName();
	}

	/** @return Gedmo\Tree\Entity\Repository\NestedTreeRepository */
	public function getRepository() {
		return parent::getRepository();
	}

	public function generateDefaultMenu() {
		$frontMenu = new Menu;
		$frontMenu->name = 'front';
		$frontMenu->text = 'front';
		$frontMenu->htmlClass = 'nav navbar-nav';
		$frontMenu->isMenuItem = FALSE;
		$frontMenu->secured = FALSE;
		$this->getEm()->persist($frontMenu);
		$this->getEm()->flush();
	}

}