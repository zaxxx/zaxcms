<?php

namespace ZaxCMS\Components\Menu;
use Nette,
	Gedmo,
	ZaxCMS,
	Zax;

class MenuControl extends Zax\Application\UI\Control {

	protected $showTinyLoginBox = FALSE;

	protected $menu;

	protected $menuListFactory;

	protected $tinyLoginBoxFactory;

	protected $class = 'navbar navbar-default navbar-static-top';

	protected $menuService;

	public function __construct(IMenuListFactory $menuListFactory,
	                            ZaxCMS\Model\MenuService $menuService = NULL, ZaxCMS\Components\Auth\ITinyLoginBoxFactory $tinyLoginBoxFactory) {
		$this->menuListFactory = $menuListFactory;
		$this->menuService = $menuService;
		$this->tinyLoginBoxFactory = $tinyLoginBoxFactory;
	}

	public function setMenu($menu = []) {
		$this->menu = $menu;
		return $this;
	}

	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	public function showTinyLoginBox() {
		$this->showTinyLoginBox = TRUE;
		return $this;
	}

	public function viewDefault() {}

	public function beforeRender() {
		$this->template->showTinyLoginBox = $this->showTinyLoginBox;
		$this->template->class = $this->class;
	}

	public function setHtmlClass($class) {
		$this->class = $class;
		return $this;
	}

	/** @return MenuListControl */
	public function getMenuList() {
		return $this['menu'];
	}

	/** @return ZaxCMS\Components\Auth\TinyLoginBoxControl */
	public function getLoginBox() {
		return $this['tinyLoginBox'];
	}

	/** @return MenuWrapperControl */
	public function getMenuWrapper() {
		return $this->lookup('ZaxCMS\Components\Menu\MenuWrapperControl');
	}

	protected function createComponentMenu() {
		$menuList = new MenuList($this->menu);
		if($this->menuService !== NULL)
			$menuList->setService($this->menuService);

		return $this->menuListFactory->create()
			->setMenu($menuList);
	}

	protected function createComponentTinyLoginBox() {
	    return $this->tinyLoginBoxFactory->create();
	}

}
