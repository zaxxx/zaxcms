<?php

namespace ZaxCMS\Components\Menu;
use Nette,
	Zax,
	ZaxCMS\Model,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Zax\Application\UI\Control;

class MenuWrapperControl extends Control {

	protected $menuFactory;

	protected $editFactory;

	protected $name;

	public function __construct(MenuModelFactory $menuFactory, IEditFactory $editFactory) {
		$this->menuFactory = $menuFactory;
		$this->editFactory = $editFactory;
	}

	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	public function canEditMenu() {
		return TRUE;
	}

	public function viewDefault() {

	}

	public function viewEdit() {

	}

	public function beforeRender() {

	}

	/** @return MenuControl */
	public function getMenu() {
		return $this['menu'];
	}

	/** @return EditControl */
	public function getEdit() {
		return $this['edit'];
	}

	protected function createComponentMenu() {
		$control = $this->menuFactory->create($this->name)
			->setName($this->name);
		if($this->ajaxEnabled) {
			$control->enableAjax(!$this->autoAjax);
		}
		return $control;
	}

	protected function createComponentEdit() {
		return $this->editFactory->create()
			->setName($this->name);
	}

}