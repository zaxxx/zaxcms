<?php

namespace Zax\Components\Sort;
use Nette,
	Kdyby,
	Zax,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Zax\Application\UI\Control;

class SortControl extends Control implements Zax\Model\Doctrine\IQueryObjectFilter {

	/** @persistent */
	public $sort;

	/** @persistent */
	public $asc = TRUE;

	protected $sortWhiteList = [];

	protected $defaultSort;

	public function setDefaultSort($defaultSort) {
		$this->defaultSort = $defaultSort;
		return $this;
	}

	public function setSortWhiteList(array $whiteList) {
		$this->sortWhiteList = $whiteList;
		return $this;
	}

	protected function getSort() {
		if(!in_array($this->sort, array_keys($this->sortWhiteList))) {
			return $this->defaultSort;
		}
		return $this->sort;
	}

	public function filterQueryObject(Kdyby\Doctrine\QueryObject $queryObject) {
		if($queryObject instanceof Zax\Model\Doctrine\QueryObject) {
			return $queryObject->orderBy($this->getSort(), (bool)$this->asc ? 'ASC' : 'DESC');
		}
	}

	public function viewDefault() {
		$this->template->wl = $this->sortWhiteList;
		$this->template->sort = $this->getSort();
		$this->template->asc = (bool)$this->asc;
	}

	public function beforeRender() {

	}

}