<?php

namespace ZaxCMS\AdminModule\Components\Users;

interface IUsersFactory {

    /** @return UsersControl */
    public function create();

}


trait TInjectUsersFactory {

	/** @var  IUsersFactory */
	protected $usersFactory;

	public function injectUsersFactory(IUsersFactory $usersFactory) {
		$this->usersFactory = $usersFactory;
	}

}

