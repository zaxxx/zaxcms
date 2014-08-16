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

class DeletePageFormControl extends FormControl {

	protected $pageService;

	protected $page;

	public function __construct(Model\PageService $pageService) {
		$this->pageService = $pageService;
	}

	public function setPage(Model\Page $page) {
		$this->page = $page;
		return $this;
	}

	public function viewDefault() {}

	public function beforeRender() {}

	public function createForm() {
		$f = parent::createForm();
		$f->addButtonSubmit('deleteItem', 'common.button.delete', 'trash');
		$f->addLinkSubmit('cancel', '', 'remove', $this->parent->link('this', ['view' => 'Default']));
		$f->enableBootstrap(['danger' => ['deleteItem'], 'default' => ['cancel']], TRUE, 3, 'sm', 'form-inline');
		if($this->ajaxEnabled) {
			$f->enableAjax();
		}
		return $f;
	}

	public function formSuccess(Form $form, $values) {
		$this->pageService->getEm()->remove($this->page);
		$this->pageService->getEm()->flush();

		$this->flashMessage('common.alert.entryDeleted', 'success');
		$this->parent->go('this', ['view' => 'Default']);
	}

	public function formError(Form $form) {

	}

}