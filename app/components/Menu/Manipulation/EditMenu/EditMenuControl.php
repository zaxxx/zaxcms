<?php

namespace ZaxCMS\Components\Menu;
use Nette,
	Zax,
	ZaxCMS\Model,
	Kdyby,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Zax\Application\UI\Control;

class EditMenuControl extends Control {

	protected $menu;

	protected $editMenuFormFactory;

	public function __construct(IEditMenuFormFactory $editMenuFormFactory) {
		$this->editMenuFormFactory = $editMenuFormFactory;
	}

	public function setMenu(Model\Menu $menu) {
		$this->menu = $menu;
		return $this;
	}

	public function handleClose() {
		$this->getEditControl()->close();
	}

	/** @return EditControl */
	public function getEditControl() {
		return $this->lookup('ZaxCMS\Components\Menu\EditControl');
	}

	protected function createComponentEditMenuForm() {
	    $control = $this->editMenuFormFactory->create()
		    ->setMenu($this->menu);
		if($this->ajaxEnabled) {
			$control->enableAjax(!$this->autoAjax);
		}
		return $control;
	}

	public function viewDefault() {

	}

	public function beforeRender() {

	}

}