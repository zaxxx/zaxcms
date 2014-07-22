<?php

namespace ZaxCMS\Components\Menu;
use Nette,
	Gedmo,
	ZaxCMS,
	Zax;

class MenuControl extends Zax\Application\UI\Control {

	protected $menu;

	protected $menuListFactory;

	protected $class = 'navbar navbar-default navbar-static-top';

	protected $menuService;

	public function __construct(IMenuListFactory $menuListFactory, ZaxCMS\Model\MenuService $menuService = NULL) {
		$this->menuListFactory = $menuListFactory;
		$this->menuService = $menuService;
	}

	public function setMenu($menu = []) {
		$this->menu = $menu;
		return $this;
	}

	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	public function viewDefault() {}

	public function beforeRender() {
		$this->template->class = $this->class;
	}

	public function setHtmlClass($class) {
		$this->class = $class;
		return $this;
	}

	protected function createComponentMenu() {
		$menuList = new MenuList($this->menu);
		if($this->menuService !== NULL)
			$menuList->setService($this->menuService);

		return $this->menuListFactory->create()
			->setMenu($menuList);
	}

}
