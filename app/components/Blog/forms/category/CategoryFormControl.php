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

abstract class CategoryFormControl extends AbstractFormControl {

	use Model\CMS\Service\TInjectCategoryService,
		Model\CMS\Service\TInjectArticleService;

	protected $category;

	public function setCategory(Model\CMS\Entity\Category $category) {
		$this->category = $category;
		return $this;
	}

    public function viewDefault() {}
    
    public function beforeRender() {
	    $this->template->htmlId = $this->lookupPath();
	    $this->template->category = $this->category;
    }

	abstract public function handleCancel();

    public function createForm() {
        $f = parent::createForm();

	    $id = $this->lookupPath('Nette\Application\UI\Presenter') . '-form';

	    $main = $f->addContainer('main');

	    $main->addStatic('parent', 'article.form.parentCategory')
		    ->addFilter(function($cat) {
			    return $cat === NULL ? '(' . $this->translator->translate('common.general.nothing') . ')' : $cat->title;
		    });

	    if($this->category->depth === 0) {
		    $main->addStatic('title', 'article.form.title');
	    } else {
		    $main->addText('title', 'article.form.title')
			    ->setRequired();
	    }

	    $main->addTexyArea('perex', 'article.form.perex')
		    ->getControlPrototype()->rows(5);

	    $pic = $f->addContainer('pic');
	    $this->createImageUpload($pic, $this->category->image);

	    $sidebar = $f->addContainer('sidebar');

	    if($this->category->depth !== 0) {
		    $sidebar->addCheckbox('sidebarParent', 'article.form.useParentCategorySidebar');
	    }

	    $sidebar->addTexyArea('sidebarContent', 'common.form.sidebarContent')
		    ->setOption('id', $id . '-sidebar')
		    ->getControlPrototype()
		    ->rows(15);

	    if($this->category->depth !== 0) {
		    $sidebar['sidebarParent']
			    ->addCondition($f::EQUAL, FALSE)
			    ->toggle($id . '-sidebar');
	    }

	    $footer = $f->addContainer('footer');

	    $footer->addButtonSubmit('saveCategory', 'common.button.save', 'ok');
	    $footer->addLinkSubmit('cancel', '', 'remove', $this->link('cancel!'));

	    $f->enableBootstrap(['success' => ['saveCategory'], 'default' => ['cancel']], TRUE);

	    $this->binder->entityToForm($this->category, $main);
	    $this->binder->entityToForm($this->category, $pic);
	    $this->binder->entityToForm($this->category, $sidebar);

	    return $f;
    }
    
    public function formSuccess(Form $form, $values) {
        if($form->submitted === $form['footer-saveCategory']) {
	        $this->binder->formToEntity($form['main'], $this->category);
	        $this->binder->formToEntity($form['pic'], $this->category);
	        $this->binder->formToEntity($form['sidebar'], $this->category);

	        $this->categoryService->persist($this->category);
	        $this->categoryService->flush();

	        // Process delete image
	        if(isset($values->pic->deleteImg) && $values->pic->deleteImg) {
		        $this->deleteImage($this->category->image);
		        $this->category->image = NULL;

		        $this->categoryService->persist($this->category);
		        $this->categoryService->flush();
	        }

	        // Process upload image
	        if($values->pic->img instanceof Nette\Http\FileUpload && $values->pic->img->isOk()) {
		        $this->category->image = $this->processImageUpload($values->pic->img, 'category', $this->category->id . '-' . $this->category->getSlugName());
		        $this->categoryService->persist($this->category);
		        $this->categoryService->flush();
	        }

	        $this->categoryService->persist($this->category);
	        $this->categoryService->flush();

	        $this->categoryService->invalidateCache();
	        $this->articleService->invalidateCache();

	        $this->flashMessage('article.alert.categorySaved', 'success');
	        $this->presenter->redirect('Blog:category', ['category-slug' => $this->category->slug]);
        }
    }
    
    public function formError(Form $form) {
        
    }

}