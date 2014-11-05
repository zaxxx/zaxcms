<?php

namespace ZaxCMS\AdminModule\Components\Mails;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

class MailsControl extends SecuredControl {

	use Model\CMS\Service\TInjectMailTemplateService,
		TInjectEditMailFormFactory,
		TInjectTestMailFormFactory;

	/** @persistent */
	public $selectMail;

	protected function getSelectedMail() {
		return $this->mailTemplateService->get($this->selectMail);
	}

    public function viewDefault() {
        
    }

	public function viewEdit() {
	    $this->template->selectedMail = $this->getSelectedMail();
	}

	public function viewTest() {
		$this->template->selectedMail = $this->getSelectedMail();
	}
    
    public function beforeRender() {
        $this->template->allMails = $this->mailTemplateService->findAll();
    }

	protected function createComponentEditMailForm() {
	    return $this->editMailFormFactory->create()
		    ->setSelectedMail($this->getSelectedMail());
	}

	protected function createComponentTestMailForm() {
	    return $this->testMailFormFactory->create()
		    ->setSelectedMail($this->getSelectedMail());
	}

}