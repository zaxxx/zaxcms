<?php

namespace ZaxCMS\FrontModule;
use Nette,
	Zax\Application\UI,
	ZaxCMS,
	Zax;

class DefaultPresenter extends BasePresenter {

	public function __construct() {
	}

	public function actionDefault() {
		//$this['webContent-index'];
	}

	public function renderDefault() {
		//$treeDao = $this->roleService->getDao();
		/** @var \Gedmo\Tree\Entity\Repository\NestedTreeRepository $treeDao */
		//$this->roleService->getAdminRole();
		//$this->template->roles = $treeDao->getChildren($this->roleService->getGuestRole(), FALSE, NULL, 'ASC', TRUE);
	}

}