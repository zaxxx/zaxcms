<?php

namespace ZaxCMS\AdminModule\Components\Users;
use Zax,
	Nette;

trait TUserSearchable {

	protected $userSearchFactory;

	public function injectUserSearch(IUserSearchFactory $userSearchFactory) {
		$this->userSearchFactory = $userSearchFactory;
	}

	protected function createComponentUserSearch() {
		return $this->userSearchFactory->create();
	}

	public function enableUserSearch() {
		$this['userSearch'];
		return $this;
	}

}