<?php

namespace ZaxCMS\Components\Article;
use Nette,
    Zax,
    ZaxCMS\Model,
    Nette\Forms\Form,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control,
    Zax\Application\UI\FormControl;

abstract class CategoryFormControl extends FormControl {

	protected $categoryService;

	protected $category;

	public function __construct(Model\CMS\Service\CategoryService $categoryService) {
		$this->categoryService = $categoryService;
	}

	public function setCategory(Model\CMS\Entity\Category $category) {
		$this->category = $category;
		return $this;
	}

    public function viewDefault() {}
    
    public function beforeRender() {}

	abstract public function handleCancel();

    public function createForm() {
        $f = parent::createForm();

	    $f->addStatic('parent', 'article.form.parentCategory')
		    ->addFilter(function($cat) {
			    return $cat->title;
		    });

	    $f->addText('title', 'article.form.title')
		    ->setRequired();

	    $f->addButtonSubmit('saveCategory', 'common.button.save', 'ok');
	    $f->addLinkSubmit('cancel', '', 'remove', $this->link('cancel!'));

	    $f->enableBootstrap(['success' => ['saveCategory'], 'default' => ['cancel']], TRUE);

	    $this->binder->entityToForm($this->category, $f);

	    return $f;
    }
    
    public function formSuccess(Form $form, $values) {
        if($form->submitted === $form['saveCategory']) {
	        $this->binder->formToEntity($form, $this->category);

	        $this->categoryService->persist($this->category);
	        $this->categoryService->flush();

	        $this->flashMessage('article.alert.categorySaved', 'success');
	        $this->presenter->redirect('Category:default', ['slug' => $this->category->slug]);
        }
    }
    
    public function formError(Form $form) {
        
    }

}