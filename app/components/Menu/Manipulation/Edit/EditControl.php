<?php

namespace ZaxCMS\Components\Menu;
use Nette,
	Zax,
	ZaxCMS\Model,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Zax\Application\UI\Control;

class EditControl extends Control {

	protected $menuService;

	protected $addMenuItemFactory;

	protected $editMenuItemFactory;

	protected $editMenuFactory;

	protected $name;

	protected $menu;

	/** @persistent */
	public $selectItem; // -1 = add

	/** @persistent */
	public $locale;

	public function __construct(Model\MenuService $menuService,
								IAddMenuItemFactory $addMenuItemFactory,
								IEditMenuItemFactory $editMenuItemFactory,
								IEditMenuFactory $editMenuFactory) {
		$this->menuService = $menuService;
		$this->addMenuItemFactory = $addMenuItemFactory;
		$this->editMenuItemFactory = $editMenuItemFactory;
		$this->editMenuFactory = $editMenuFactory;
	}

	public function close() {
		$this->getMenuWrapper()->go('this', ['view' => 'Default']);
	}

	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	public function getMenu() {
		if($this->menu === NULL) {
			$this->menu = $this->menuService->getRepository()->findOneByName($this->name);
			$this->menu->setTranslatableLocale($this->getLocale());
			$this->menuService->setLocale($this->getLocale());
			$this->menuService->refresh($this->menu);
		}
		return $this->menu;
	}

	public function viewDefault() {

	}

	public function beforeRender() {
		$this->template->root = $this->getMenu();
		$this->template->currentLocale = $this->getLocale();
		$this->template->items = $this->menuService->getChildren($this->getMenu(), TRUE);
		$this->template->availableLocales = $this->getAvailableLocales();
	}

	/** @return AddMenuItemControl */
	public function getAddMenuItem() {
		return $this['addMenuItem'];
	}

	/** @return EditMenuItemControl */
	public function getEditMenuItem() {
		return $this['editMenuItem'];
	}

	/** @return EditMenuControl */
	public function getEditMenu() {
		return $this['editMenu'];
	}

	/** @return MenuWrapperControl */
	public function getMenuWrapper() {
		return $this->lookup('ZaxCMS\Components\Menu\MenuWrapperControl');
	}

	/** @return MenuControl */
	public function getMenuControl() {
		return $this->getMenuWrapper()->getMenu();
	}

	protected function createComponentAddMenuItem() {
		$control = $this->addMenuItemFactory->create()
			->setParentMenu($this->getMenu());
		if($this->ajaxEnabled) {
			$control->enableAjax(!$this->autoAjax);
		}
		return $control;
	}

	protected function createComponentEditMenuItem() {
		$control = $this->editMenuItemFactory->create()
			->setMenuItem($this->menuService->getDao()->findOneById($this->selectItem));
		if($this->ajaxEnabled) {
			$control->enableAjax(!$this->autoAjax);
		}
		return $control;
	}

	protected function createComponentEditMenu() {
		$control = $this->editMenuFactory->create()
			->setMenu($this->getMenu());
		if($this->ajaxEnabled) {
			$control->enableAjax(!$this->autoAjax);
		}
		return $control;
	}

}