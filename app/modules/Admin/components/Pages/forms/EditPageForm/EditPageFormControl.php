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

	/** @secured Pages, Edit */
    public function viewDefault() {}

	/** @secured Pages, Edit */
    public function beforeRender() {}

	protected function successFlashMessage() {
		$this->flashMessage('common.alert.changesSaved', 'success');
	}
    
    public function createSubmitButtons(Form $form) {
	    $form->addButtonSubmit('savePage', 'page.button.editPage', 'file');
	    $form->addLinkSubmit('cancel', '', 'remove', $this->link('cancel!'));
	    $form->enableBootstrap(['primary' => ['savePage'], 'default' => ['cancel']], TRUE);
    }
    
    public function formError(Form $form) {
	    $this->flashMessage('common.alert.changesError', 'danger');
    }

}