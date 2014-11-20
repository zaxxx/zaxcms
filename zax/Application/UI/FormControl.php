<?php

namespace Zax\Application\UI;
use Nette,
	Zax,
	Kdyby;

abstract class FormControl extends SecuredControl {

	/** @var IFormFactory */
	protected $formFactory;

	/** @var Zax\Forms\IBinder|NULL */
	protected $binder;

	/** @var Zax\Forms\ILabelFactory|NULL */
	protected $labelFactory;

	/** @var Zax\Model\IService|NULL */
	protected $service;

	public function injectDependencies(IFormFactory $formFactory,
									   Zax\Forms\IBinder $binder = NULL,
									   Zax\Forms\ILabelFactory $labelFactory = NULL) {
		$this->formFactory = $formFactory;
		$this->binder = $binder;
		$this->labelFactory = $labelFactory;
	}

	public function setService(Zax\Model\IService $service) {
		$this->service = $service;
		return $this;
	}

	/**
	 * @return Form
	 */
	public function createForm() {
		$form = $this->formFactory->create();
		$form->onSuccess[] = [$this, 'formSuccess'];
		$form->onError[] = [$this, 'formError'];
		return $form;
	}

	protected function createComponentForm() {
		return $this->createForm();
	}

	abstract protected function formError(Nette\Forms\Form $form);

	abstract public function formSuccess(Nette\Forms\Form $form, $values);

	/* HELPERS */

	protected function label($text, $hint = NULL, $hintIcon = 'question-circle') {
		if($this->labelFactory === NULL) {
			return $text;
		}
		return $this->labelFactory->getLabel($text, $hint, $hintIcon);
	}

}