<?php

namespace ZaxCMS\Components\Auth;

interface IChangePasswordFactory {

    /** @return ChangePasswordControl */
    public function create();

}


trait TInjectChangePasswordFactory {

	protected $changePasswordFactory;

	public function injectChangePasswordFactory(IChangePasswordFactory $changePasswordFactory) {
		$this->changePasswordFactory = $changePasswordFactory;
	}

}

