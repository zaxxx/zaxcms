<?php

namespace ZaxCMS\Components\Auth;
use Nette,
    Zax,
	ZaxCMS,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class ChangePasswordControl extends Control {

	protected $formFactory;

	protected $user;

	protected $changePassword;

	public function __construct(ZaxUI\FormFactory $formFactory,
								Nette\Security\User $user,
								Model\CMS\ChangePassword $changePassword) {
		$this->formFactory = $formFactory;
		$this->user = $user;
		$this->changePassword = $changePassword;
	}

    public function viewDefault() {
        
    }
    
    public function beforeRender() {
        
    }

	protected function createComponentChangePasswordForm() {
	    $form = $this->formFactory->create();

		$form->addGroup('user.form.oldPassword');

		$form->addPassword('oldPassword', 'system.form.password')
			->setRequired();

		$form->addGroup('user.form.newPassword');

		$form->addPassword('password', 'system.form.password')
			->setRequired();
		$form->addPassword('password2', 'system.form.passwordRepeat')
			->setRequired()
			->addRule($form::EQUAL, 'system.form.passwordNotMatch', $form['password']);

		$form->addStatic('passwordNote', '')
			->setValue($this->translator->translate('user.form.strongPasswordNote'));

		$form->addGroup();

		$form->addButtonSubmit('changePassword', 'user.button.changePassword', 'lock');

		$form->addProtection();

		$form->enableBootstrap(['success' => ['changePassword']]);

		$form->onSuccess[] = [$this, 'changePasswordFormSuccess'];

		return $form;
	}

	public function changePasswordFormSuccess(NetteUI\Form $form, $values) {
		if($this->user->isLoggedIn() && $form->submitted === $form['changePassword']) {
			try {
				$this->changePassword->changePassword($values->oldPassword, $values->password);
				$this->flashMessage('user.alert.passwordChanged', 'success');
				$this->presenter->redirect(':Front:Default:default');
			} catch (ZaxCMS\Security\InvalidPasswordException $e) {
				$form['oldPassword']->addError($this->translator->translate('auth.error.invalidPassword'));
			}
		}
	}

}