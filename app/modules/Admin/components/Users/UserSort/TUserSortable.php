<?php

namespace ZaxCMS\AdminModule\Components\Users;
use Zax,
	Nette;

trait TUserSortable {

	protected $userSortFactory;

	public function injectUserSort(IUserSortFactory $userSortFactory) {
		$this->userSortFactory = $userSortFactory;
	}

	protected function createComponentUserSort() {
	    return $this->userSortFactory->create();
	}

	public function enableUserSort() {
		$this['userSort'];
		return $this;
	}

}