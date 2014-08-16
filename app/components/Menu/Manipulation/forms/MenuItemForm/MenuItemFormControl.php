<?php

namespace ZaxCMS\Components\Menu;
use Nette,
    Zax,
    ZaxCMS\Model,
    Nette\Forms\Form,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control,
	Kdyby,
	Zax\Application\UI\FormControl;

abstract class MenuItemFormControl extends FormControl {

	protected $router;

	protected $menuItem;

	protected $menuService;

	public function __construct(Nette\Application\IRouter $router, Model\MenuService $menuService) {
		$this->router = $router;
		$this->menuService = $menuService;
	}

	public function setMenuItem(Model\Menu $menu) {
		if(!$menu->isMenuItem) {
			throw new ObjectNotMenuItemException('This is menu list, not menu item!');
		}
		$this->menuItem = $menu;
		return $this;
	}

	/** @return EditControl */
	public function getEditControl() {
		return $this->lookup('ZaxCMS\Components\Menu\EditControl');
	}

    public function viewDefault() {}
    
    public function beforeRender() {}

	abstract protected function createSubmitButtons(Form $form);

	abstract protected function saveMenuItem(Model\Menu $menu, Form $form);
    
    public function createForm() {
        $f = parent::createForm();

	    $f->addStatic('localeFlag', 'webContent.form.locale')
		    ->setDefaultValue($this->getEditControl()->getLocale());

	    $f->addText('name', 'menu.form.uniqueName')
		    ->setRequired()
		    ->addRule($f::PATTERN, 'form.error.alphanumeric', '([a-zA-Z0-9]+)');

	    $f->addText('text', 'menu.form.text');
	    $f->addText('htmlClass', 'menu.form.htmlClass');
	    $f->addText('htmlTarget', 'menu.form.htmlTarget');

	    $f->addText('href', 'menu.form.url');
	    $f->addText('nhref', 'menu.form.nhref');
	    $f->addNeonTextArea('nhrefParams', 'menu.form.nhrefParams');

	    $this->createSubmitButtons($f);

	    $f->autofocus('name');

	    $this->binder->entityToForm($this->menuItem, $f);

	    return $f;
    }
    
    public function formSuccess(Form $form, $values) {
	    $menuItem = $this->binder->formToEntity($form, $this->menuItem);

	    // Transform URL to Nette format if possible
	    if(!empty($values->href) && strpos($values->href, $this->template->baseUri) === 0) {
		    $url = new Nette\Http\UrlScript(str_replace($this->template->baseUri, '', $values->href));

		    $menuItem->nhref = $this->router->urlToNHref($url);
		    $menuItem->nhrefParams = $this->router->urlToParams($url, ['locale']);
		    $menuItem->href = NULL;

		    // Project changes into form
		    $form = $this->binder->entityToForm($menuItem, $form);
	    }

	    $this->saveMenuItem($menuItem, $form);
    }
    
    public function formError(Form $form) {
	    $this->flashMessage('menu.alert.changesError');
    }

	/** @secured Menu, Edit */
	protected function createComponentForm() {
		return parent::createComponentForm();
	}

}