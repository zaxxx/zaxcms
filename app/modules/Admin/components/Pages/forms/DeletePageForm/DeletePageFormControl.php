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

	public function __construct(Model\CMS\Service\PageService $pageService) {
		$this->pageService = $pageService;
	}

	public function setPage(Model\CMS\Entity\Page $page) {
		$this->page = $page;
		return $this;
	}

	/** @secured Pages, Delete */
	public function viewDefault() {}

	/** @secured Pages, Delete */
	public function beforeRender() {}

	public function handleCancel() {
		$this->parent->go('this', ['view' => 'Default', 'page' => NULL]);
	}

	public function createForm() {
		$f = parent::createForm();
		$f->addButtonSubmit('deleteItem', 'common.button.delete', 'trash');
		$f->addLinkSubmit('cancel', '', 'remove', $this->link('cancel!'));
		$f->addProtection();
		$f->enableBootstrap(['danger' => ['deleteItem'], 'default' => ['cancel']], TRUE, 3, 'sm', 'form-inline');
		return $f;
	}

	public function formSuccess(Form $form, $values) {
		if($form->submitted === $form['deleteItem']) {
			$this->pageService->remove($this->page);
			$this->pageService->flush();
			$this->pageService->invalidateCache();

			$this->flashMessage('common.alert.entryDeleted', 'success');
			$this->parent->go('this', ['view' => 'Default', 'page' => NULL]);
		}
	}

	public function formError(Form $form) {

	}

}