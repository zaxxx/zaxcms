<?php

namespace ZaxCMS\AdminModule\Components\Mails;

interface IMailsFactory {

    /** @return MailsControl */
    public function create();

}


trait TInjectMailsFactory {

	/** @var IMailsFactory */
	protected $mailsFactory;

	public function injectMailsFactory(IMailsFactory $mailsFactory) {
		$this->mailsFactory = $mailsFactory;
	}

}

