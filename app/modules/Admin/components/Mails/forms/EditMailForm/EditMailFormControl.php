<?php

namespace ZaxCMS\AdminModule\Components\Mails;
use Nette,
    Zax,
    ZaxCMS\Model,
    Nette\Forms\Form,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control,
    Zax\Application\UI\FormControl;

class EditMailFormControl extends FormControl {

	protected $mailTemplateService;

	protected $mailTemplate;

	public function __construct(Model\CMS\Service\MailTemplateService $mailTemplateService) {
		$this->mailTemplateService = $mailTemplateService;
	}

	public function setSelectedMail(Model\CMS\Entity\MailTemplate $mailTemplate) {
		$this->mailTemplate = $mailTemplate;
		return $this;
	}

    public function viewDefault() {}
    
    public function beforeRender() {}

    public function createForm() {
        $f = parent::createForm();

	    $f->addStatic('name', 'common.form.uniqueName');

	    $f->addEmail('from', 'mail.form.from')
		    ->setRequired();

	    $f->addText('subject', 'mail.form.subject')
		    ->setRequired();

	    $f->addTextArea('template', 'mail.form.template')
		    ->setRequired();

	    $f->addCheckbox('toggleCopy', 'mail.form.copy')
	        ->setDefaultValue(count($this->mailTemplate->cc) + count($this->mailTemplate->bcc) > 0 ? TRUE : FALSE)
		    ->addCondition($f::EQUAL, TRUE)
			    ->toggle($this->getUniqueId() . '-cc')
			    ->toggle($this->getUniqueId() . '-bcc');

	    $f->addArrayTextArea('cc', 'mail.form.cc')
		    ->setOption('id', $this->getUniqueId() . '-cc');

	    $f->addArrayTextArea('bcc', 'mail.form.bcc')
		    ->setOption('id', $this->getUniqueId() . '-bcc');

	    $f->addButtonSubmit('saveSettings', 'common.button.save', 'ok');

	    $this->binder->entityToForm($this->mailTemplate, $f);

	    $f->enableBootstrap(['success' => ['saveSettings']]);

	    return $f;
    }
    
    public function formSuccess(Form $form, $values) {
        if($form->submitted === $form['saveSettings']) {
	        $this->binder->formToEntity($form, $this->mailTemplate);

	        if(!$values->toggleCopy) {
		        $this->mailTemplate->cc = NULL;
		        $this->mailTemplate->bcc = NULL;
	        }

	        $this->mailTemplateService->persist($this->mailTemplate);
	        $this->mailTemplateService->flush();

	        $this->flashMessage('common.alert.changesSaved', 'success');
	        $this->go('this');
        }
    }
    
    public function formError(Form $form) {
        
    }

}