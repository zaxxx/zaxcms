<?php

namespace ZaxCMS\Components\Menu;
use Nette,
    Zax,
    ZaxCMS\Model,
    Nette\Forms\Form,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control,
    Zax\Application\UI\FormControl;

class DeleteMenuItemFormControl extends FormControl {

	protected $menuService;

	protected $menuItem;

	public function __construct(Model\MenuService $menuService) {
		$this->menuService = $menuService;
	}

	public function setMenuItem(Model\Menu $menu) {
		if(!$menu->isMenuItem) {
			throw new ObjectNotMenuItemException('This is menu list, not menu item!');
		}
		$this->menuItem = $menu;
		return $this;
	}

	/** @return EditControl */
	public function getEditControl() {
		return $this->lookup('ZaxCMS\Components\Menu\EditControl');
	}

	/** @return EditMenuItemControl */
	public function getEditMenuItem() {
		return $this->lookup('ZaxCMS\Components\Menu\EditMenuItemControl');
	}

    public function viewDefault() {}
    
    public function beforeRender() {}
    
    public function createForm() {
        $f = parent::createForm();
	    $f->addButtonSubmit('deleteItem', 'common.button.delete', 'trash');
	    $f->addLinkSubmit('cancel', '', 'remove', $this->getEditMenuItem()->link('this', ['view' => 'Default']));
	    $f->enableBootstrap(['danger' => ['deleteItem'], 'default' => ['cancel']], TRUE, 3, 'sm', 'form-inline');
	    if($this->ajaxEnabled) {
		    $f->enableAjax();
	    }
	    return $f;
    }
    
    public function formSuccess(Form $form, $values) {
	    $this->menuService->getEm()->remove($this->menuItem);
	    $this->menuService->getEm()->flush();
	    $this->menuService->invalidateCache();

	    $this->flashMessage('common.alert.entryDeleted', 'success');
	    $this->getEditControl()->go('this', ['selectItem' => NULL, 'editMenuItem-view' => 'Default']);
    }
    
    public function formError(Form $form) {
        
    }

}