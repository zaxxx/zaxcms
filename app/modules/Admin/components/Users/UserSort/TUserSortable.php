<?php

namespace ZaxCMS\AdminModule\Components\Users;
use Zax,
	Nette;

trait TUserSortable {

	/** @var Zax\Components\Sort\ISortFactory */
	protected $sortFactory;

	public function injectSort(Zax\Components\Sort\ISortFactory $sortFactory) {
		$this->sortFactory = $sortFactory;
	}

	protected function createComponentUserSort() {
	    return $this->sortFactory->create()
		    ->setDefaultSort('a.id')
		    ->setSortWhiteList(
			    [
				    'a.id' => 'common.form.id',
				    'a.name' => 'user.form.username',
				    'c.registeredAt' => 'user.form.registered',
				    'c.passwordLastChangedAt' => 'user.form.passwordChanged'
			    ]
		    );
	}

	public function enableUserSort() {
		$this['userSort'];
		return $this;
	}

}