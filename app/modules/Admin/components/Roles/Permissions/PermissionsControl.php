<?php

namespace ZaxCMS\AdminModule\Components\Roles;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

class PermissionsControl extends SecuredControl {

	protected $role;

	protected $aclService;

	protected $permissionService;

	protected $aclFactory;

	protected $authorizator;

	public function __construct(Model\CMS\Service\AclService $aclService,
								Model\CMS\Service\PermissionService $permissionService,
								Model\CMS\AclFactory $aclFactory,
								Nette\Security\IAuthorizator $authorizator) {
		$this->aclService = $aclService;
		$this->permissionService = $permissionService;
		$this->aclFactory = $aclFactory;
		$this->authorizator = $authorizator;
	}

	public function setRole(Model\CMS\Entity\Role $role) {
		$this->role = $role;
		return $this;
	}

	public function getResultSet() {
		return $this->aclService->fetchQueryObject(new Model\CMS\Query\AclQuery);
	}

	/** @secured Roles, Edit */
    public function viewDefault() {
        
    }

	/** @secured Roles, Edit */
	public function handleAllow($role, $permission) {
		$this->aclService->allow($this->role, $this->permissionService->get($permission));
		$this->aclService->flush();
		$this->authorizator = $this->aclFactory->invalidateCache();
		$this->flashMessage('common.alert.changesSaved', 'success');
		$this->go('this');
	}

	/** @secured Roles, Edit */
	public function handleDeny($role, $permission) {
		$this->aclService->deny($this->role, $this->permissionService->get($permission));
		$this->aclService->flush();
		$this->authorizator = $this->aclFactory->invalidateCache();
		$this->flashMessage('common.alert.changesSaved', 'success');
		$this->go('this');
	}

	/** @secured Roles, Edit */
    public function beforeRender() {
	    $this->template->permissions = $this->permissionService->fetchQueryObject(new Model\CMS\Query\PermissionQuery($this->getLocale()));
	    $this->template->authorizator = $this->authorizator;
	    $this->template->role = $this->role;
    }

}