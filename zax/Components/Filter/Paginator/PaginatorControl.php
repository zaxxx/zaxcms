<?php

namespace Zax\Components\Filter;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class PaginatorControl extends FilterControl {

	/** @persistent */
	public $page = 1;

	/** @var Nette\Utils\Paginator */
	protected $paginator;

	public function __construct() {
		$this->paginator = new Nette\Utils\Paginator;
		$this->paginator->setPage($this->page);
		$this->paginator->setItemsPerPage(10);
	}

	public function setItemsPerPage($itemsPerPage) {
		$this->paginator->setItemsPerPage($itemsPerPage);
		return $this;
	}

	protected function applyFilter() {
		return $this->resultSet->applyPaginator($this->paginator);
	}

    public function viewDefault() {
        
    }
    
    public function beforeRender() {
	    $this->template->paginator = $this->paginator;
    }

	public function loadState(array $params) {
		parent::loadState($params);
		if(isset($params['page'])) {
			$this->paginator->setPage($params['page']);
		}
	}

}