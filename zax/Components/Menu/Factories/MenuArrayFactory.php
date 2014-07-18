<?php

namespace Zax\Components\Menu;

class MenuArrayFactory implements IMenuFactory {
    
    protected $menus;
    
    protected $menuListFactory;
    
    public function __construct(IMenuListFactory $menuListFactory, $menus = []) {
        $this->menus = $menus;
        $this->menuListFactory = $menuListFactory;
    }
    
    /** @return MenuControl */
    public function create($menu) {
        return new MenuControl($this->menuListFactory, $this->menus[$menu]);
    }
    
}
