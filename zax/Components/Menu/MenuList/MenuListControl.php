<?php

namespace Zax\Components\Menu;
use Nette,
	Nette\Application\UI as NetteUI,
    Zax;

class MenuListControl extends Zax\Application\UI\SecuredControl {

	protected $menu;

	protected $menuItemFactory;

	public function __construct(IMenuItemFactory $menuItemFactory) {
		$this->menuItemFactory = $menuItemFactory;
	}

	public function setMenu(MenuList $menu) {
		$this->menu = $menu;
		return $this;
	}
    
    public function viewDefault() {}

	public function beforeRenderItemsOnly() {
		$this->template->menu = $this->menu;
	}
    
    public function beforeRender() {
	    $this->template->menu = $this->menu;
    }

	protected function createComponentItem() {
		return new NetteUI\Multiplier(function($id) {
			return $this->menuItemFactory->create()
				->setItem($this->menu->getMenuItem($id));
		});
	}
    
}
