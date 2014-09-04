<?php

namespace ZaxCMS\Components\Navigation;
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

	public function __construct(Nette\Application\IRouter $router, Model\CMS\Service\MenuService $menuService) {
		$this->router = $router;
		$this->menuService = $menuService;
	}

	public function setMenuItem(Model\CMS\Entity\Menu $menu) {
		$this->menuItem = $menu;
		return $this;
	}

	/** @return EditControl */
	public function getEditControl() {
		return $this->lookup('ZaxCMS\Components\Navigation\EditControl');
	}

    public function viewDefault() {}
    
    public function beforeRender() {}

	abstract protected function createSubmitButtons(Form $form);

	abstract protected function saveMenuItem(Model\CMS\Entity\Menu $menu, Form $form);
    
    public function createForm() {
        $f = parent::createForm();

	    $f->addStatic('localeFlag', 'webContent.form.locale')
		    ->setDefaultValue($this->getEditControl()->getLocale())
		    ->addFilter(function($locale) {
			    return $this->translator->translate('common.lang.' . $locale);
		    });



	    $f->addText('text', 'common.form.displayText')
		    ->addRule(Form::MAX_LENGTH, NULL, 255);

	    $f->addIconSelect('icon', 'menu.form.icon');

	    $f->addText('title', 'menu.form.title')
		    ->addRule(Form::MAX_LENGTH, NULL, 511);

	    $f->addText('href', 'menu.form.url')
		    ->addRule(Form::MAX_LENGTH, NULL, 511);

	    $f->addText('nhref', 'menu.form.nhref')
		    ->addRule(Form::MAX_LENGTH, NULL, 255);

	    $f->addNeonTextArea('nhrefParams', 'menu.form.nhrefParams');

	    // TOGGLE advancedStuff
	    $f->addCheckbox('advancedStuff', 'common.form.advancedOptions')
		    ->addCondition($f::EQUAL, TRUE)
		        ->toggle($this->getUniqueId() . '-uniqueName')
		        ->toggle($this->getUniqueId() . '-htmlTarget')
		        ->toggle($this->getUniqueId() . '-htmlClass');

	    $f->addText('name', 'common.form.uniqueName')
		    ->setOption('id', $this->getUniqueId() . '-uniqueName')
		    ->addCondition(Form::FILLED)
			    ->addRule(Form::MAX_LENGTH, NULL, 63)
			    ->addRule($f::PATTERN, 'form.error.alphanumeric', '([a-zA-Z0-9]+)');

	    $f->addText('htmlClass', 'menu.form.htmlClass')
		    ->addRule(Form::MAX_LENGTH, NULL, 255)
		    ->setOption('id', $this->getUniqueId() . '-htmlClass');

	    $f->addText('htmlTarget', 'menu.form.htmlTarget')
		    ->addRule(Form::MAX_LENGTH, NULL, 63)
		    ->setOption('id', $this->getUniqueId() . '-htmlTarget');

	    $this->createSubmitButtons($f);

	    $f->autofocus('text');

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

	    try {
	        $this->saveMenuItem($menuItem, $form);
	    } catch (Kdyby\Doctrine\DuplicateEntryException $ex) {
		    $form['name']->addError($this->translator->translate('form.error.duplicateName'));
	    }
    }
    
    public function formError(Form $form) {
	    $this->flashMessage('common.alert.changesError', 'danger');
    }

	/** @secured Menu, Edit */
	protected function createComponentForm() {
		return parent::createComponentForm();
	}

}