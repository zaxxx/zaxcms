<?php

namespace ZaxCMS\Components\Auth;

interface ILogoutButtonFactory {

    /** @return LogoutButtonControl */
    public function create();

}


trait TInjectLogoutButtonFactory {

	protected $logoutButtonFactory;

	public function injectLogoutButtonFactory(ILogoutButtonFactory $logoutButtonFactory) {
		$this->logoutButtonFactory = $logoutButtonFactory;
	}

}

