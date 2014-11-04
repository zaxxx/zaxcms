<?php

namespace ZaxCMS\Model\CMS;
use Zax,
	ZaxCMS,
	Nette;

class SendMail extends Nette\Object {

	protected $mailTemplateService;

	protected $mailer;

	public function __construct(ZaxCMS\Model\CMS\Service\MailTemplateService $mailTemplateService,
								Nette\Mail\IMailer $mailer) {
		$this->mailTemplateService = $mailTemplateService;
		$this->mailer = $mailer;
	}

	public function sendEmail($key, $to, $parameters = []) {
		$mailTemplate = $this->mailTemplateService->getBy(['name' => $key]);
		$message = $mailTemplate->toMessage($parameters);
		$message->addTo($to);
		$this->mailer->send($message);
	}

}