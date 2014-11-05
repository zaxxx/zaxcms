<?php

namespace ZaxCMS\Components\Auth;

interface IAdminPanelButtonFactory {

    /** @return AdminPanelButtonControl */
    public function create();

}


trait TInjectAdminPanelButtonFactory {

	protected $adminPanelButtonFactory;

	public function injectAdminPanelButtonFactory(IAdminPanelButtonFactory $adminPanelButtonFactory) {
		$this->adminPanelButtonFactory = $adminPanelButtonFactory;
	}

}

