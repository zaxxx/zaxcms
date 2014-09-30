<?php

namespace ZaxCMS\AdminModule\Components\Users;
use Nette,
    Zax,
    ZaxCMS\Model,
    Nette\Forms\Form,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control,
    Zax\Application\UI\FormControl;

class EditUserFormControl extends UserFormControl {

    public function formSuccess(Form $form, $values) {
        if($form->submitted === $form['saveUser']) {
            $this->binder->formToEntity($form, $this->selectedUser);
            $this->userService->persist($this->selectedUser);
            $this->userService->flush();
            $this->aclFactory->invalidateCache();

            $this->flashMessage('common.alert.changesSaved', 'success');
            $this->parent->go('this', ['view' => 'Default']);
        }
    }

}