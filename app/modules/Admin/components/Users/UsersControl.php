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
		TUserSortable,
		TUserSearchable,

		Model\CMS\Service\TInjectUserService,
		Model\CMS\Service\TInjectRoleService,
		TInjectProfileFactory,
		TInjectAddUserFormFactory;

	protected $defaultLinkParams = [
		'profile-basicInfo-view' => NULL,
		'profile-securityInfo-view' => NULL
	];

	/** @persistent */
	public $selectUser;

	protected function getService() {
		return $this->userService;
	}

	protected function createQueryObject() {
		return new Model\CMS\Query\UserQuery($this->getLocale());
	}

	protected function getSelectedUser() {
		return $this->userService->get($this->selectUser);
	}

	/** @secured Users, Use */
    public function viewDefault() {
        
    }

	/** @secured Users, Use */
	public function viewProfile() {

	}

	/** @secured Users, Add */
    public function viewAdd() {

    }

	/** @secured Users, Use */
    public function beforeRender() {
        $this->template->users = $this->getFilteredResultSet();
    }

	/** @secured Users, Use */
	protected function createComponentProfile() {
	    return $this->profileFactory->create()
		    ->setSelectedUser($this->getSelectedUser());
	}

	/** @secured Users, Add */
    protected function createComponentAddUserForm() {
        return $this->addUserFormFactory->create()
            ->setSelectedUser($this->userService->create());
    }

}