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

    public function viewDefault() {}
    
    public function beforeRender() {}
    
    public function createForm() {
        $f = parent::createForm();
	    $f->addButtonSubmit('deleteItem', 'common.button.delete', 'trash');
	    $f->addLinkSubmit('cancel', '', 'remove', $this->link('this', ['view' => 'Default']));
	    $f->enableBootstrap(['danger' => ['deleteItem'], 'default' => ['cancel']], TRUE, 3, 'sm', 'form-inline');
	    if($this->ajaxEnabled) {
		    $f->enableAjax();
	    }
    }
    
    public function formSuccess(Form $form, $values) {
	    $this->menuService->getEm()->remove($this->menuItem);
	    $this->menuService->getEm()->flush();

	    $this->flashMessage('Deleted');
	    $this->lookup('ZaxCMS\Components\Menu\EditControl')->go('this', ['selectItem' => 0]);
    }
    
    public function formError(Form $form) {
        
    }

}