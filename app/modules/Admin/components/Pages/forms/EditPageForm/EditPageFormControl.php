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

class EditPageFormControl extends PageFormControl {

    public function viewDefault() {}
    
    public function beforeRender() {}

	protected function successFlashMessage() {
		$this->flashMessage('common.alert.changesSaved', 'success');
	}
    
    public function createSubmitButtons(Form $form) {
	    $form->addButtonSubmit('savePage', 'page.button.editPage', 'file');
	    $form->addButtonSubmit('cancel', '', 'remove');
	    $form->enableBootstrap(['primary' => ['savePage'], 'default' => ['cancel']], TRUE);
    }
    
    public function formError(Form $form) {
	    $this->flashMessage('common.alert.changesError', 'danger');
    }

}