<?php

namespace ZaxCMS\AdminModule\Components\Users;
use Nette,
	Kdyby,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class UserSortControl extends Control implements Zax\Model\Doctrine\IQueryObjectFilter {

	/** @persistent */
	public $sort;

	/** @persistent */
	public $asc = TRUE;

	protected $sortWhiteList = [
		'a.id' => 'common.form.id',
		'a.name' => 'user.form.username'
	];

	protected function getSort() {
		if(!in_array($this->sort, array_keys($this->sortWhiteList))) {
			return 'a.id';
		}
		return $this->sort;
	}

	public function filterQueryObject(Kdyby\Doctrine\QueryObject $queryObject) {
		/** @var Model\CMS\Query\UserQuery $queryObject */
		return $queryObject->orderBy($this->getSort(), (bool)$this->asc ? 'ASC' : 'DESC');
	}

    public function viewDefault() {
        $this->template->wl = $this->sortWhiteList;
	    $this->template->sort = $this->getSort();
	    $this->template->asc = (bool)$this->asc;
    }
    
    public function beforeRender() {
        
    }

}