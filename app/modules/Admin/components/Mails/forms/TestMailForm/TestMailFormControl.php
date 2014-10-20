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

class TestMailFormControl extends FormControl {

	protected $mailTemplate;

	protected $sendMail;

	public function __construct(Model\CMS\SendMail $sendMail) {
		$this->sendMail = $sendMail;
	}

	public function setSelectedMail(Model\CMS\Entity\MailTemplate $mailTemplate) {
		$this->mailTemplate = $mailTemplate;
		return $this;
	}

    public function viewDefault() {}
    
    public function beforeRender() {}
    
    public function createForm() {
        $f = parent::createForm();

	    $f->addEmail('to', 'mail.form.to')
		    ->setRequired();

	    $params = $this->mailTemplate->getTemplateParameters();
	    foreach($params as $param) {
		    $f->addText($param, '{$' . $param . '}')
			    ->setDefaultValue(rand(10, 100));
	    }

	    $f->addButtonSubmit('sendTestMail', 'mail.button.testMail', 'envelope');

	    $f->enableBootstrap(['success' => ['sendTestMail']]);

	    return $f;
    }
    
    public function formSuccess(Form $form, $values) {
        if($form->submitted === $form['sendTestMail']) {
	        $processedParams = [];
	        $params = $this->mailTemplate->getTemplateParameters();
	        foreach($params as $param) {
		        $processedParams[$param] = $values->$param;
	        }
	        $arrays = $this->mailTemplate->getTemplateArrays();
	        foreach($arrays as $tplArray) {
		        $processedParams[$tplArray] = [];
	        }

	        $this->sendMail->sendEmail($this->mailTemplate->name, $values->to, $processedParams);

	        $this->flashMessage('mail.alert.testMailSent', 'success');
	        $this->go('this');
        }
    }
    
    public function formError(Form $form) {
        
    }

}