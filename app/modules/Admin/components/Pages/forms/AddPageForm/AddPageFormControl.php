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

class AddPageFormControl extends PageFormControl {

    public function viewDefault() {}
    
    public function beforeRender() {}

	protected function successFlashMessage() {
		$this->flashMessage('common.alert.newEntrySaved', 'success');
	}
    
    public function createSubmitButtons(Form $form) {
	    $form->addButtonSubmit('savePage', 'page.button.addPage', 'file');
	    $form->addButtonSubmit('cancel', '', 'remove');
	    $form->enableBootstrap(['primary' => ['savePage'], 'default' => ['cancel']], TRUE);
    }
    
    public function formError(Form $form) {
	    $this->flashMessage('common.alert.newEntryError', 'danger');
    }

}