<?php

namespace ZaxCMS\Components\Blog;
use Nette,
	Zax,
	ZaxCMS\Model,
	Nette\Forms\Form,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Zax\Application\UI\Control,
	Zax\Application\UI\FormControl;

class DeleteCategoryFormControl extends FormControl {

	use Model\CMS\Service\TInjectArticleService,
		Model\CMS\Service\TInjectCategoryService;

	protected $category;

	public function setCategory(Model\CMS\Entity\Category $category) {
		$this->category = $category;
		return $this;
	}

	public function viewDefault() {}

	public function beforeRender() {}

	public function createForm() {
		$f = parent::createForm();
		$f->addButtonSubmit('deleteItem', 'common.button.delete', 'trash');
		$f->addLinkSubmit('cancel', '', 'remove', $this->parent->link('this', ['view' => 'Default']));
		$f->addProtection();
		$f->enableBootstrap(['danger' => ['deleteItem'], 'default' => ['cancel']], TRUE, 3, 'sm', 'form-inline');
		return $f;
	}

	public function formSuccess(Form $form, $values) {
		if($form->submitted === $form['deleteItem'] && $this->category->depth > 0) {
			$catSlug = $this->category->parent->slug;
			$this->categoryService->remove($this->category);
			$this->categoryService->flush();
			$this->categoryService->invalidateCache();
			$this->articleService->invalidateCache();
			$this->flashMessage('common.alert.entryDeleted', 'success');
			$this->presenter->redirect('Blog:category', ['category-slug' => $catSlug]);
		}
	}

	public function formError(Form $form) {

	}

}