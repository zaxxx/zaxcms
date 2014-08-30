<?php

namespace ZaxCMS\Components\Navigation;
use Nette,
	Zax,
	ZaxCMS,
	ZaxCMS\Model,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Zax\Application\UI\Control;

class EditControl extends Control {

	protected $menuService;

	protected $addMenuItemFactory;

	protected $editMenuItemFactory;

	protected $localeSelectFactory;

	protected $name;

	protected $menu;

	/** @persistent */
	public $selectItem; // -1 = add

	/** @persistent */
	public $selectMenu;

	public function __construct(Model\MenuService $menuService,
								IAddMenuItemFactory $addMenuItemFactory,
								IEditMenuItemFactory $editMenuItemFactory,
								ZaxCMS\Components\LocaleSelect\ILocaleSelectFactory $localeSelectFactory) {
		$this->menuService = $menuService;
		$this->addMenuItemFactory = $addMenuItemFactory;
		$this->editMenuItemFactory = $editMenuItemFactory;
		$this->localeSelectFactory = $localeSelectFactory;
	}

	public function getLocale() {
		return $this['localeSelect']->getLocale();
	}

	public function close() {

	}

	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	public function getMenu() {
		if($this->menu === NULL) {
			$this->menuService->setLocale($this->getLocale());
			$this->menu = $this->menuService->getByName($this->name);
			$this->menu->setTranslatableLocale($this->getLocale());
		}
		return $this->menu;
	}

	public function getSelectedMenu() {
		if($this->selectMenu !== NULL) {
			return $this->menuService->getRepository()->findOneById($this->selectMenu);
		}
		return $this->getMenu();
	}

	/** @secured Menu, Edit */
	public function viewDefault() {

	}

	public function beforeRender() {
		$this->template->root = $this->getMenu();
		$this->template->currentLocale = $this->getLocale();
		$this->template->items = $this->menuService->getChildren($this->getMenu(), FALSE, 'lft', 'ASC', FALSE);
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

	/** @secured Menu, Edit */
	protected function createComponentAddMenuItem() {
		return $this->addMenuItemFactory->create()
			->setParentMenu($this->getSelectedMenu());
	}

	/** @secured Menu, Edit */
	protected function createComponentEditMenuItem() {
		return $this->editMenuItemFactory->create()
			->setMenuItem($this->menuService->getDao()->findOneById($this->selectItem));
	}

	/** @secured Menu, Edit */
	protected function createComponentLocaleSelect() {
	    return $this->localeSelectFactory->create();
	}

}