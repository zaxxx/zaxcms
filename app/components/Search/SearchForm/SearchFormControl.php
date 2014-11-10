<?php

namespace ZaxCMS\Components\Search;
use Nette,
    Zax,
    ZaxCMS\Model,
    Nette\Forms\Form,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control,
    Zax\Application\UI\FormControl;

class SearchFormControl extends FormControl {

	protected $q;

	public function setSearch($q) {
		$this->q = $q;
		return $this;
	}

    public function viewDefault() {}
    
    public function beforeRender() {}


    
    public function createForm() {
        $f = parent::createForm();

	    $f->addText('q', '')
		    ->setDefaultValue($this->q)
		    ->setAttribute('placeholder', $this->translator->translate('common.form.search'));

	    $f->addButtonSubmit('doSearch', '', 'search');

	    $f->enableBootstrap(['primary' => ['doSearch']], FALSE, 3, 'sm', 'form-inline');

	    return $f;
    }
    
    public function formSuccess(Form $form, $values) {
        $this->presenter->redirect('Search:default', ['search-q' => $values->q]);
    }
    
    public function formError(Form $form) {
        
    }

}