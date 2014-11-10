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

class DeleteArticleFormControl extends FormControl {

	use Model\CMS\Service\TInjectArticleService;

	protected $article;

	public function setArticle(Model\CMS\Entity\Article $article) {
		$this->article = $article;
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
        if($form->submitted === $form['deleteItem']) {
	        $catSlug = $this->article->category->slug;
	        $this->articleService->remove($this->article);
	        $this->articleService->flush();
	        $this->articleService->invalidateCache();
	        $this->flashMessage('common.alert.entryDeleted', 'success');
	        $this->presenter->redirect('Blog:category', ['category-slug' => $catSlug]);
        }
    }
    
    public function formError(Form $form) {
        
    }

}