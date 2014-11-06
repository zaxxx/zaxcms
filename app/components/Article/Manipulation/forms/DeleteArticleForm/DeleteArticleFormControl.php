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

class DeleteArticleFormControl extends FormControl {

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
	    if($this->ajaxEnabled) {
		    $f->enableAjax();
	    }
	    return $f;
    }
    
    public function formSuccess(Form $form, $values) {
        
    }
    
    public function formError(Form $form) {
        
    }

}