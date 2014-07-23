<?php

namespace ZaxCMS\Components\Menu;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Kdyby,
	Zax\Application\UI\FormControl;

class EditMenuFormControl extends FormControl {

	/** @var Model\Menu */
	protected $menu;

	protected $menuService;

	public function __construct(Model\MenuService $menuService) {
		$this->menuService = $menuService;
	}

	public function setMenu(Model\Menu $menu) {
		$this->menu = $menu;
		return $this;
	}

	public function getMenu() {
		return $this->menu;
	}

    public function viewDefault() {
        
    }
    
    public function beforeRender() {
        
    }

	/** @return EditMenuControl */
	public function getEditMenu() {
		return $this->lookup('ZaxCMS\Components\Menu\EditMenuControl');
	}

	/** @return EditControl */
	public function getEditControl() {
		return $this->lookup('ZaxCMS\Components\Menu\EditControl');
	}

	public function attached($presenter) {
		parent::attached($presenter);
		$this->menu->setTranslatableLocale($this->getEditControl()->getLocale());
		$this->menuService->refresh($this->menu);
	}

	public function formSuccess(Nette\Forms\Form $form, $values) {
		$this->binder->formToEntity($form, $this->menu);
		$this->menu->setTranslatableLocale($this->getEditControl()->getLocale());
		try {
			$this->menuService->getEm()->flush();
			$this->menuService->invalidateCache();

			$this->flashMessage('menu.alert.changesSaved');
			$this->getEditMenu()->go('this', ['view' => 'Default']);
		} catch (Kdyby\Doctrine\DuplicateEntryException $ex) {
			$form['name']->addError($this->translator->translate('form.error.duplicateEntry'));
		}
	}

	public function formError(Nette\Forms\Form $form) {
		$this->flashMessage('menu.alert.changesError');
	}

	public function createForm() {
		$f = parent::createForm();

		$f->addStatic('localeFlag', 'webContent.form.locale')
			->setDefaultValue($this->getEditControl()->getLocale());
		$f->addText('name', 'menu.form.uniqueName')
			->setRequired()
			->addRule($f::PATTERN, 'form.error.alphanumeric', '([a-zA-Z0-9]+)');
		$f->addText('text', 'menu.form.text');
		$f->addText('htmlClass', 'menu.form.htmlClass');

		$f->addProtection();

		$f->addButtonSubmit('editMenu', 'common.button.edit', 'pencil');
		$f->addLinkSubmit('cancel', '', 'remove', $this->getEditMenu()->link('close!'));

		$f->enableBootstrap(['success' => ['editMenu'], 'default' => ['cancel']], TRUE);

		$f->autofocus('name');

		if($this->ajaxEnabled) {
			$f->enableAjax();
		}

		$this->binder->entityToForm($this->menu, $f);

		return $f;
	}

}