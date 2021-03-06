<?php

namespace ZaxCMS\AdminModule\Components\Roles;
use Nette,
    Zax,
	Kdyby,
    ZaxCMS\Model,
    Nette\Forms\Form,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control,
    Zax\Application\UI\FormControl;

abstract class RoleFormControl extends FormControl {

	protected $role;

	protected $roleService;

	public function __construct(Model\CMS\Service\RoleService $roleService) {
		$this->roleService = $roleService;
	}

    public function viewDefault() {}
    
    public function beforeRender() {}

	public function setRole(Model\CMS\Entity\Role $role) {
		$this->role = $role;
		return $this;
	}

	abstract protected function createSubmitButtons(Form $form);

	abstract protected function successFlashMessage();

	public function handleCancel() {
		$this->parent->go('this', ['view' => 'Default', 'selectRole' => NULL]);
	}

    public function createForm() {
        $f = parent::createForm();

	    $f->addText('name', 'common.form.uniqueName')
		    ->setRequired()
		    ->addRule(Form::MAX_LENGTH, NULL, 63)
		    ->addRule($f::PATTERN, 'form.error.alphanumeric', '([a-zA-Z0-9]+)');
	    $f->addText('displayName', 'common.form.displayName');
	    $f->addTextArea('description', 'common.form.description');
	    if($this->role->parent !== NULL) {
		    $f->addStatic('parent', 'role.form.inheritsFrom')
			    ->addFilter(function($parent) {
				    return $parent->displayName;
			    });
	    }

	    $f->addProtection();

	    $this->createSubmitButtons($f);

	    $f->autofocus('name');

	    $this->binder->entityToForm($this->role, $f);

	    return $f;
    }
    
    public function formSuccess(Form $form, $values) {
	    if($form->submitted === $form['saveRole']) {
		    $this->binder->formToEntity($form, $this->role);
		    $this->role->setTranslatableLocale($this->parent['localeSelect']->getLocale());
		    try {
		        $this->roleService->persist($this->role);
			    $this->roleService->flush();
			    $this->successFlashMessage();
			    $this->parent->onUpdate();
			    $this->parent->go('this', ['view' => 'Edit', 'selectRole' => $this->role->id]);
		    } catch (Kdyby\Doctrine\DuplicateEntryException $ex) {
			    $form['name']->addError($this->translator->translate('form.error.duplicateName'));
		    }
	    }
    }
    
    public function formError(Form $form) {
        
    }

}