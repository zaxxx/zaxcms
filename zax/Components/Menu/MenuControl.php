<?php

namespace Zax\Components\Menu;
use Nette,
	Gedmo,
    Zax;

class MenuControl extends Zax\Application\UI\Control {
    
    protected $menu;
    
    protected $menuListFactory;

	protected $class = 'navbar navbar-default navbar-static-top';

	protected $menuRepository;
    
    public function __construct(IMenuListFactory $menuListFactory) {
        $this->menuListFactory = $menuListFactory;
    }

	public function setMenu($menu = []) {
		$this->menu = $menu;
		return $this;
	}

	public function setRepository(Gedmo\Tree\Entity\Repository\NestedTreeRepository $repository) {
		$this->menuRepository = $repository;
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
	    if($this->menuRepository !== NULL)
		    $menuList->setRepository($this->menuRepository);

        return $this->menuListFactory->create()
	        ->setMenu($menuList);
    }
    
}
