<?php

namespace ZaxCMS\AdminModule\Components\Users;

interface IBasicInfoFactory {

    /** @return BasicInfoControl */
    public function create();

}


trait TInjectBasicInfoFactory {

	protected $basicInfoFactory;

	public function injectBasicInfoFactory(IBasicInfoFactory $basicInfoFactory) {
		$this->basicInfoFactory = $basicInfoFactory;
	}

}

