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

	protected $menuService;

	public function __construct(Model\MenuService $menuService) {
		$this->menuService = $menuService;
	}

	public function setMenu(Model\Menu $menu) {
		$this->menu = $menu;
		return $this;
	}

	protected function createComponentEditMenuForm() {
		$f = (new MenuForm)->createMenuForm($this);

		$f->addButtonSubmit('editMenu', 'common.button.edit', 'pencil');
		//$f->addLinkSubmit('cancel', '', 'remove', $this->link('close!'));

		$f->enableBootstrap(['success' => ['editMenu'], 'default' => ['cancel']], TRUE);

		$f->autofocus('name');

		if($this->ajaxEnabled) {
			$f->enableAjax();
		}

		$this->binder->entityToForm($this->menu, $f);

		$f->onSuccess[] = function(ZaxUI\Form $form, $values) {
			$this->binder->formToEntity($form, $this->menu);
			try {
				$this->menuService->getEm()->persist($this->menu);
				$this->menuService->getEm()->flush();
				$this->flashMessage('menu.alert.changesSaved');
				$this->parent->go('this', ['view' => 'Default']);
			} catch (Kdyby\Doctrine\DuplicateEntryException $ex) {
				$form['name']->addError($this->translator->translate('form.error.duplicateEntry'));
			}
		};

		$f->onError[] = function() {
			$this->flashMessage('menu.alert.changesError');
		};

		return $f;
	}

    public function viewDefault() {
        
    }
    
    public function beforeRender() {
        
    }

}