<?php

namespace Zax\Components\Paginator;
use Nette,
	Kdyby,
    Zax,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class PaginatorControl extends Control implements Zax\Model\Doctrine\IResultSetFilter {

	/** @persistent */
	public $page = 1;

	protected $itemsPerPage = 30;

	protected $paginator;

	public function setItemsPerPage($perPage) {
		$this->itemsPerPage = $perPage;
		return $this;
	}

	protected function createPaginator() {
		$paginator = new Nette\Utils\Paginator;
		$paginator->setItemsPerPage($this->itemsPerPage);
		$paginator->setPage((int)$this->page);
		return $paginator;
	}

	protected function getPaginator() {
		if($this->paginator === NULL) {
			$this->paginator = $this->createPaginator();
		}
		return $this->paginator;
	}

	public function filterResultSet(Kdyby\Doctrine\ResultSet $resultSet) {
		$resultSet->applyPaginator($this->getPaginator());
	}

    public function viewDefault() {
	    $this->template->paginator = $this->getPaginator();
    }
    
    public function beforeRender() {

    }

	public function beforeRenderPager() {

	}

	public function loadState(array $params) {
		parent::loadState($params);
		if(isset($params['page'])) {
			$this->getPaginator()->setPage($params['page']);
		}
	}

}