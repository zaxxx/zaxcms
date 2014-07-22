<?php

namespace Zax\Application\UI;
use Nette,
	Zax,
	Kdyby;

abstract class FormControl extends SecuredControl {

	/** @var Zax\Application\UI\FormFactory */
	protected $formFactory;

	/** @var Zax\Forms\IBinder|NULL */
	protected $binder;

	/** @var Zax\Model\IService|NULL */
	protected $service;

	public function injectDependencies(Zax\Application\UI\FormFactory $formFactory,
									   Zax\Forms\IBinder $binder = NULL) {
		$this->formFactory = $formFactory;
		$this->binder = $binder;
	}

	public function setService(Zax\Model\IService $service) {
		$this->service = $service;
		return $this;
	}

	/**
	 * Translated form factory
	 *
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

}