<?php

namespace ZaxCMS\AdminModule\Components\Users;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class UsersControl extends Zax\Components\Collections\FilterableControl {

	use Zax\Components\Collections\TPaginable,
		TRoleFilterable,
		TUserSortable;

	/** @persistent */
	public $selectUser;

	protected $userService;

	protected $profileFactory;

	public function __construct(Model\CMS\Service\UserService $userService,
								IProfileFactory $profileFactory) {
		$this->userService = $userService;
		$this->profileFactory = $profileFactory;
	}

	protected function getService() {
		return $this->userService;
	}

	protected function createQueryObject() {
		return new Model\CMS\Query\UserQuery;
	}

	protected function getSelectedUser() {
		return $this->userService->get($this->selectUser);
	}

    public function viewDefault() {
        
    }

	public function viewProfile() {

	}
    
    public function beforeRender() {
        $this->template->users = $this->getFilteredResultSet();
    }

	protected function createComponentProfile() {
	    return $this->profileFactory->create()
		    ->setSelectedUser($this->getSelectedUser());
	}

}