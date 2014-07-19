<?php

namespace Zax\Components\Menu;
use Nette,
    Zax;

class MenuControl extends Zax\Application\UI\Control {
    
    protected $menu;
    
    protected $menuListFactory;

	protected $class = 'navbar navbar-default navbar-static-top';
    
    public function __construct(IMenuListFactory $menuListFactory) {
        $this->menuListFactory = $menuListFactory;
    }

	public function setMenu($menu = []) {
		$this->menu = $menu;
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
        return $this->menuListFactory->create()
	        ->setMenu(new MenuList($this->menu));
    }
    
}
