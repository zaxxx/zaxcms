<?php

namespace ZaxCMS\AdminModule\Components\Users;

interface IProfileFactory {

    /** @return ProfileControl */
    public function create();

}


trait TInjectProfileFactory {

	protected $profileFactory;

	public function injectProfileFactory(IProfileFactory $profileFactory) {
		$this->profileFactory = $profileFactory;
	}

}

