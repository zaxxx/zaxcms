<?php

namespace ZaxCMS\Components\Navigation;
use Nette,
	Zax,
	ZaxCMS,
	ZaxCMS\Model,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Zax\Application\UI\SecuredControl;

class EditControl extends SecuredControl {

	use Model\CMS\Service\TInjectMenuService,
		TInjectAddMenuItemFactory,
		TInjectEditMenuItemFactory,
		ZaxCMS\Components\LocaleSelect\TInjectLocaleSelectFactory;

	protected $defaultLinkParams = [
		'editMenuItem-editMenuItemForm-form-icon-selectedValue' => NULL
	];

	protected $name;

	protected $menu;

	/** @persistent */
	public $selectItem; // -1 = add

	/** @persistent */
	public $selectMenu;

	public function getLocale() {
		return $this['localeSelect']->getLocale();
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
			$this->menuService->refresh($this->menu);
		}
		return $this->menu;
	}

	public function getSelectedMenu() {
		if($this->selectMenu !== NULL) {
			$this->menuService->setLocale($this->getLocale());
			return $this->menuService->get($this->selectMenu);
		}
		return $this->getMenu();
	}

	/** @secured Menu, Use */
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
			->setMenuItem($this->menuService->get($this->selectItem));
	}

	/** @secured Menu, Use */
	protected function createComponentLocaleSelect() {
	    return $this->localeSelectFactory->create();
	}

}