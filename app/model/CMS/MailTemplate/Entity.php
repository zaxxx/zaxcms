<?php

namespace ZaxCMS\Model\CMS\Entity;
use Zax,
	ZaxCMS,
	Nette,
	Latte,
	Kdyby,
	Kdyby\Doctrine\Entities\BaseEntity,
	Doctrine,
	Gedmo\Translatable\Translatable,
	Gedmo\Mapping\Annotation as Gedmo,
	Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 *
 * @property-read int $id
 */
class MailTemplate extends BaseEntity {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=63, unique=TRUE)
	 */
	protected $name;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $from;

	/**
	 * @ORM\Column(type="array", nullable=TRUE)
	 */
	protected $cc;

	/**
	 * @ORM\Column(type="array", nullable=TRUE)
	 */
	protected $bcc;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $subject;

	/**
	 * @ORM\Column(type="text")
	 */
	protected $template;


	/****** MESSAGE FACTORY ******/

	protected $latteFactory;

	public function injectLatteFactory(Nette\Bridges\ApplicationLatte\ILatteFactory $latteFactory) {
		$this->latteFactory = $latteFactory;
	}

	/**
	 * @param array $templateParameters
	 * @return Nette\Mail\Message
	 */
	public function toMessage($templateParameters = []) {
		$latte = $this->latteFactory instanceof Nette\Bridges\ApplicationLatte\ILatteFactory
					? $this->latteFactory->create()
					: new Latte\Engine;

		$latte->setLoader(new Latte\Loaders\StringLoader);

		$htmlBody = $latte->renderToString($this->template, $templateParameters);

		$mail = new Nette\Mail\Message;
		$mail->setFrom($this->from);
		$mail->setSubject($this->subject);
		$mail->setHtmlBody($htmlBody);

		if($this->cc !== NULL) {
			foreach($this->cc as $cc) {
				$mail->addCc($cc);
			}
		}

		if($this->bcc !== NULL) {
			foreach($this->bcc as $bcc) {
				$mail->addBcc($bcc);
			}
		}

		return $mail;
	}

	public function getTemplateParameters() {
		$params = [];
		preg_match_all('#\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)#', $this->template, $params);
		return $params[1];
	}

}