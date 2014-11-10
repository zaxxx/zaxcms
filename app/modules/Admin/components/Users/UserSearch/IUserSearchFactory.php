<?php

namespace ZaxCMS\AdminModule\Components\Users;

interface IUserSearchFactory {

    /** @return UserSearchControl */
    public function create();

}


trait TInjectUserSearchFactory {

	protected $userSearchFactory;

	public function injectUserSearchFactory(IUserSearchFactory $userSearchFactory) {
		$this->userSearchFactory = $userSearchFactory;
	}

}

