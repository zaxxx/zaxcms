<?php

namespace Zax\Components\Menu;
use Nette,
	Zax,
	Zax\Application\UI\Control;

class MenuItemControl extends Control {

	protected $item;

	protected $menuListFactory;

	public function __construct(IMenuListFactory $menuListFactory) {
		$this->menuListFactory = $menuListFactory;
	}

	public function setItem(MenuItem $item) {
		$this->item = $item;
		return $this;
	}

	public function viewDefault() {

	}

	public function beforeRender() {
		$this->template->item = $this->item;
	}

	protected function createComponentSubmenu() {
		return $this->menuListFactory->create()
			->setMenu($this->item->getSubmenu());
	}

}