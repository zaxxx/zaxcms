<?php

namespace ZaxCMS\Components\Menu;
use Nette,
	Zax,
	Kdyby,
	ZaxCMS\Model,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Zax\Application\UI\Control;

// TODO: DRY
class AddMenuItemControl extends Control {

	protected $menuService;

	protected $parentMenu;

	protected $router;

	public function __construct(Model\MenuService $menuService, Nette\Application\IRouter $router) {
		$this->menuService = $menuService;
		$this->router = $router;
	}

	public function setParentMenu(Model\Menu $menu) {
		$this->parentMenu = $menu;
		return $this;
	}

	public function handleClose() {
		$this->parent->go('this', ['selectItem' => NULL]);
	}

	protected function createComponentAddItemForm() {
		$f = (new MenuItemForm)->createMenuItemForm($this, $this->parent->getLocale());

		$f->addButtonSubmit('addItem', 'common.button.add', 'plus');
		$f->addLinkSubmit('cancel', '', 'remove', $this->link('close!'));

		$f->enableBootstrap(['success' => ['addItem'], 'default' => ['cancel']], TRUE);

		$f->autofocus('name');

		if($this->ajaxEnabled) {
			$f->enableAjax();
		}

		$f->onSuccess[] = function(ZaxUI\Form $form, $values) {
			$menuItem = new Model\Menu;
			$menuItem->isMenuItem = TRUE;
			$menuItem->secured = FALSE;
			$menuItem->setTranslatableLocale($this->parent->getLocale());
			$this->binder->formToEntity($form, $menuItem);
			if(!empty($values->href) && strpos($values->href, $this->template->baseUri) === 0) {
				$request = $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript(str_replace($this->template->baseUri, '', $values->href))));
				if($request) {
					$params = $request->getParameters();
					$menuItem->nhref = ':' . $request->presenterName . ':' . $params['action'];
					unset($params['action']);
					$menuItem->nhrefParams = $params;
					$menuItem->href = NULL;
				}
			}
			try {
				$this->menuService->getRepository()->persistAsLastChildOf($menuItem, $this->parentMenu);
				$this->menuService->getEm()->flush();
				$this->flashMessage('menu.alert.newEntrySaved');
				$this->parent->go('this', ['selectItem' => $menuItem->id]);
			} catch (Kdyby\Doctrine\DuplicateEntryException $ex) {
				$form['name']->addError($this->translator->translate('form.error.duplicateEntry'));
			}
		};

		$f->onError[] = function() {
			$this->flashMessage('menu.alert.newEntryError');
		};

		return $f;
	}

	public function viewDefault() {

	}

	public function beforeRender() {

	}

}