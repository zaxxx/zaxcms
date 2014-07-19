<?php

namespace ZaxCMS\Components\Menu;
use Nette,
    Zax,
	Kdyby,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class EditMenuItemControl extends Control {

	protected $menuItem;

	protected $menuService;

	protected $router;

	public function __construct(Model\MenuService $menuService, Nette\Application\IRouter $router) {
		$this->menuService = $menuService;
		$this->router = $router;
	}

	public function setMenuItem(Model\Menu $menuItem) {
		$this->menuItem = $menuItem;
		return $this;
	}

    public function viewDefault() {
        
    }
	
	public function viewDelete() {
	    
	}
    
    public function beforeRender() {

    }

	protected function getMenu() {
		return $this->lookup('ZaxCMS\Components\MenuWrapperControl')['menu'];
	}

	public function handleMoveUp() {
		$this->menuService->moveUp($this->menuItem);
		$this->menuService->getEm()->refresh($this->menuItem->parent);
		$this->go('this');
	}

	public function handleMoveDown() {
		$this->menuService->moveDown($this->menuItem);
		$this->menuService->getEm()->refresh($this->menuItem->parent);
		$this->go('this');
	}

	public function handleClose() {
		$this->parent->go('this', ['selectItem' => NULL]);
	}

	protected function createComponentDeleteItemForm() {
		$f = $this->createForm();
		$f->addButtonSubmit('deleteItem', 'common.button.delete', 'trash');
		$f->addLinkSubmit('cancel', '', 'remove', $this->link('this', ['view' => 'Default']));
		$f->enableBootstrap(['danger' => ['deleteItem'], 'default' => ['cancel']], TRUE, 3, 'sm', 'form-inline');
		if($this->ajaxEnabled) {
			$f->enableAjax();
		}

		$f->onSuccess[] = function(ZaxUI\Form $form, $values) {
			$this->menuService->getEm()->remove($this->menuItem);
			$this->menuService->getEm()->flush();

			$this->flashMessage('Deleted');
			$this->parent->go('this', ['selectItem' => 0]);
		};

		return $f;
	}

	protected function createComponentEditItemForm() {
		$f = (new MenuItemForm)->createMenuItemForm($this);


		$f->addButtonSubmit('editItem', 'common.button.edit', 'pencil');
		$f->addLinkSubmit('cancel', '', 'remove', $this->link('close!'));

		$f->enableBootstrap(['success' => ['editItem'], 'default' => ['cancel']], TRUE);

		$f->autofocus('name');

		if($this->ajaxEnabled) {
			$f->enableAjax();
		}

		$f = $this->binder->entityToForm($this->menuItem, $f);

		$f->onSuccess[] = function(ZaxUI\Form $form, $values) {
			$this->binder->formToEntity($form, $this->menuItem);

			if(!empty($values->href) && strpos($values->href, $this->template->baseUri) === 0) {
				$request = $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript(str_replace($this->template->baseUri, '', $values->href))));
				if($request) {
					$params = $request->getParameters();
					$this->menuItem->nhref = ':' . $request->presenterName . ':' . $params['action'];
					unset($params['action']);
					$this->menuItem->nhrefParams = $params;
					$this->menuItem->href = NULL;
				}
			}
			try {
				$this->menuService->getEm()->persist($this->menuItem);
				$this->menuService->getEm()->flush();
				$this->binder->entityToForm($this->menuItem, $form);
				$this->flashMessage('menu.alert.changesSaved');
				$this->parent->go('this', ['selectItem' => $this->menuItem->id]);
			} catch (Kdyby\Doctrine\DuplicateEntryException $ex) {
				$form['name']->addError($this->translator->translate('form.error.duplicateEntry'));
			}
		};

		$f->onError[] = function() {
			$this->flashMessage('menu.alert.changesError');
		};

		return $f;
	}

}