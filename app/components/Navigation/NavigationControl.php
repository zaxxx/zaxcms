<?php

namespace ZaxCMS\Components\Navigation;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

class NavigationControl extends SecuredControl {

	const STYLE_STACKED = 'stacked',
		STYLE_JUSTIFIED = 'justified';

	protected $dropdownCaret = FALSE;

	protected $menuService;

	protected $editFactory;

	protected $menu;

	public $classes = [
		'ul' => [],
		'li' => [],
		'sub-ul' => []
	];

	public function __construct(Model\MenuService $menuService,
								IEditFactory $editFactory) {
		$this->menuService = $menuService;
		$this->editFactory = $editFactory;
	}

	public function enableDropdownCaret() {
		$this->dropdownCaret = TRUE;
		return $this;
	}

	public function setClasses($ul = [], $li = [], $subUl = []) {
		$this->classes = ['ul' => $ul, 'li' => $li, 'sub-ul' => $subUl];
		return $this;
	}

	public function setBSNavbarClasses() {
		$this->classes = [
			'ul' => ['nav', 'navbar-nav'],
			'li' => [],
			'sub-ul' => ['dropdown-menu']
		];
		return $this;
	}

	public function setBSListClasses() {
		$this->classes = [
			'ul' => ['list-group'],
			'li' => ['list-group-item'],
			'sub-ul' => ['list-group']
		];
		return $this;
	}

	public function setBSTabsClasses() {
		$this->classes = [
			'ul' => ['nav', 'nav-tabs'],
			'li' => [],
			'sub-ul' => ['dropdown-menu']
		];
		return $this;
	}

	public function setBSPillsClasses($style = NULL) {
		$this->classes = [
			'ul' => ['nav', 'nav-pills'],
			'li' => [],
			'sub-ul' => ['dropdown-menu']
		];
		if($style)
			$this->classes['ul'][] = 'nav-' . $style;
		return $this;
	}

	public function setMenuName($name) {
		$this->menu = $this->menuService->getByName($name);
		return $this;
	}

	public function viewDefault() {

    }

	/** @secured Menu, Edit */
	public function viewEdit() {
	    
	}
    
    public function beforeRender() {
	    $this->template->classes = $this->classes;
	    $this->template->root = $this->menu;
	    $this->template->menu = $this->menuService->getChildren($this->menu, FALSE, 'lft');
	    $this->template->dropdownCaret = $this->dropdownCaret;
    }

	/** @secured Menu, Edit */
	protected function createComponentEdit() {
	    return $this->editFactory->create()
		    ->setName($this->menu->name);
	}

}