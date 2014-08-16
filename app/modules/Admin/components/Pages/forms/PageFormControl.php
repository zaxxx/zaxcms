<?php

namespace ZaxCMS\AdminModule\Components\Pages;
use Nette,
    Zax,
    ZaxCMS\Model,
    Nette\Forms\Form,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control,
    Zax\Application\UI\FormControl;

abstract class PageFormControl extends FormControl {

	protected $page;

	protected $pageService;

	public function __construct(Model\PageService $pageService) {
		$this->pageService = $pageService;
	}

	public function setPage(Model\Page $page) {
		$this->page = $page;
		return $this;
	}

    public function viewDefault() {}
    
    public function beforeRender() {}

	abstract protected function createSubmitButtons(Form $form);

	abstract protected function successFlashMessage();

	public function handleCancel() {
		$this->parent->go('this', ['view' => 'Default', 'page' => NULL]);
	}

	public function createForm() {
		$f = parent::createForm();

		$f->addText('name', 'common.form.uniqueName')
			->setRequired()
			->addRule(Form::MAX_LENGTH, NULL, 63)
			->addRule($f::PATTERN, 'form.error.alphanumeric', '([a-zA-Z0-9]+)');

		$f->addText('title', 'common.form.title')
			->setRequired()
			->addRule(Form::MAX_LENGTH, NULL, 255);

		$this->createSubmitButtons($f);

		if($this->ajaxEnabled) {
			$f->enableAjax();
		}

		$f = $this->binder->entityToForm($this->page, $f);

		return $f;
	}

	public function formSuccess(Form $form, $values) {
		if($form->submitted === $form['savePage']) {
			$page = $this->page;
			$this->binder->formToEntity($form, $page);
			$this->pageService->persist($page);
			$this->pageService->flush();

			$this->successFlashMessage();
		}
		$this->parent->go('this', ['view' => 'Default']);
	}
    
    public function formError(Form $form) {
        
    }

}