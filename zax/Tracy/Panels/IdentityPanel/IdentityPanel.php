<?php

namespace Zax\Tracy\Panels;
use Zax,
	Tracy,
	Latte,
	Nette;

class IdentityPanel extends Panel {

	protected $user;

	public function __construct(Nette\Bridges\ApplicationLatte\ILatteFactory $latteFactory,
								Nette\Security\User $user) {
		parent::__construct($latteFactory);
		$this->user = $user;
	}

	protected function createTab() {
		$tpl = $this->createTemplate('tab');
		$tpl->user = $this->user;
		return $tpl;
	}

	public function createPanel() {
		$form = $this->createForm();
		$form->addText('role')
			->setDefaultValue($this->user->roles[0])
			->setAttribute('placeholder', 'Role');
		$form->addSubmit('setRole', 'Set')
			->getControlPrototype()->addClass('btn btn-default');

		if($form->isSubmitted() && strlen($form->values->role) > 2) {
			$this->user->identity->setRoles([$form->values->role]);
		}

		$tpl = $this->createTemplate('panel');
		$tpl->form = $form;
		return $tpl;
	}

}