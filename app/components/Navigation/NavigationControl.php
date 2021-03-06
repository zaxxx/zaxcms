<?php

namespace ZaxCMS\Components\Navigation;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

class NavigationControl extends SecuredControl {

	use Model\CMS\Service\TInjectMenuService,
		TInjectEditFactory;

	protected $defaultLinkParams = [
		'edit-selectItem' => NULL,
		'edit-selectMenu' => NULL,
		'edit-addMenuItem-addMenuItemForm-form-icon-selectedValue' => NULL,
		'edit-editMenuItem-editMenuItemForm-form-icon-selectedValue' => NULL,
		'edit-localeSelect-locale' => NULL,
		'edit-view' => NULL
	];

	const STYLE_STACKED = 'stacked',
		STYLE_JUSTIFIED = 'justified';

	protected $dropdownCaret = FALSE;

	protected $dropdown = FALSE;

	protected $menuName;

	protected $menu;

	public $classes = [
		'ul' => [],
		'li' => [],
		'sub-ul' => []
	];

	protected $markActiveStrategy;

	public function __construct() {
		$this->markActiveStrategy = new MarkActiveStrategies\StrategyList();
	}

	public function getMarkActiveStrategy() {
		return $this->markActiveStrategy;
	}

	public function setMarkActiveStrategy(MarkActiveStrategies\IStrategy $strategy) {
		$this->markActiveStrategy = $strategy;
		return $this;
	}

	public function attached($presenter) {
		parent::attached($presenter);
		$this->redrawControl(NULL, FALSE);
	}

	public function enableDropdownCaret() {
		$this->dropdownCaret = TRUE;
		return $this;
	}

	public function enableDropdown() {
		$this->dropdown = TRUE;
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
			'sub-ul' => ['nav', 'nav-pills']
		];
		if($this->dropdown) {
			$this->classes['sub-ul'] = ['dropdown-menu'];
		}
		return $this;
	}

	public function setBSPillsClasses($style = NULL) {
		$this->classes = [
			'ul' => ['nav', 'nav-pills'],
			'li' => [],
			'sub-ul' => ['nav', 'nav-pills']
		];
		if($style) {
			$this->classes['ul'][] = 'nav-' . $style;
			$this->classes['sub-ul'][] = 'nav-' . $style;
		}
		if($this->dropdown) {
			$this->classes['sub-ul'] = ['dropdown-menu'];
		}
		return $this;
	}

	public function setMenuName($name) {
		$this->menuName = $name;
		return $this;
	}

	protected function getMenu() {
		if($this->menu === NULL) {
			$this->menuService->setLocale($this->presenter->getLocale());
			$this->menu = $this->menuService->getByName($this->menuName, TRUE);
		}
		return $this->menu;
	}

	public function isActive(Model\CMS\Entity\Menu $menuItem) {
		if($this->markActiveStrategy->isValidForItem($menuItem)) {
			return $this->markActiveStrategy->isActive($menuItem);
		}
		return FALSE;
	}

	public function viewDefault() {

    }

	/** @secured Menu, Use */
	public function viewEdit() {
	    
	}

	/** @secured Menu, Edit */
	public function handleRefresh() {
		$this->redrawControl('navigation');
		$this->go('this');
	}
    
    public function beforeRender() {
	    $this->template->classes = $this->classes;
	    $this->template->root = $this->getMenu();
	    $this->template->menu = $this->menuService->getChildren($this->menu, FALSE, 'lft');
	    $this->template->dropdownCaret = $this->dropdownCaret;
	    $this->template->dropdown = $this->dropdown;
    }

	/** @secured Menu, Use */
	protected function createComponentEdit() {
	    return $this->editFactory->create()
		    ->setName($this->getMenu()->name);
	}

}