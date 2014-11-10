<?php

namespace ZaxCMS\AdminModule;
use Nette,
	Zax\Application\UI,
	ZaxCMS,
	Zax;

class MailsPresenter extends BasePresenter {

	protected $mailsFactory;

	public function __construct(ZaxCMS\AdminModule\Components\Mails\IMailsFactory $mailsFactory) {
		$this->mailsFactory = $mailsFactory;
	}

	protected function createComponentMails() {
	    return $this->mailsFactory->create()
		    ;//->enableAjax();
	}

}