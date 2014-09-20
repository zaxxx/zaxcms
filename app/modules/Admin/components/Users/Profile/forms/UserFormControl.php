<?php

namespace ZaxCMS\AdminModule\Components\Users;
use Nette,
    Zax,
    ZaxCMS\Model,
    Nette\Forms\Form,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control,
    Zax\Application\UI\FormControl;

abstract class UserFormControl extends FormControl {

	protected $selectedUser;

	protected $userService;

	protected $aclFactory;

	public function __construct(Model\CMS\Service\UserService $userService,
								Model\CMS\AclFactory $aclFactory) {
		$this->userService = $userService;
		$this->aclFactory = $aclFactory;
	}

    public function viewDefault() {}
    
    public function beforeRender() {}

	public function setSelectedUser(Model\CMS\Entity\User $user) {
		$this->selectedUser = $user;
		return $this;
	}

	public function handleCancel() {
		$this->parent->go('this', ['view' => 'Default']);
	}
    
    public function createForm() {
        $f = parent::createForm();

	    $f->addText('name', 'user.form.username');
	    $f->addText('email', 'user.form.email');

	    $this->binder->entityToForm($this->selectedUser, $f);

	    $f->addButtonSubmit('saveUser', 'common.button.save', 'ok');
	    $f->addLinkSubmit('cancel', '', 'remove', $this->link('cancel!'));

	    $f->enableBootstrap(['success' => ['saveUser'], 'default' => ['cancel']], TRUE);

	    return $f;
    }

	abstract protected function successFlashMessage();
    
    public function formSuccess(Form $form, $values) {
        if($form->submitted === $form['saveUser']) {
	        $this->binder->formToEntity($form, $this->selectedUser);
	        $this->userService->persist($this->selectedUser);
	        $this->userService->flush();
	        $this->aclFactory->invalidateCache();

	        $this->successFlashMessage();
	        $this->parent->go('this', ['view' => 'Default']);
        }
    }
    
    public function formError(Form $form) {
        
    }

}