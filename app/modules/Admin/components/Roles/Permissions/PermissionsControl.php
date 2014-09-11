<?php

namespace ZaxCMS\AdminModule\Components\Roles;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class PermissionsControl extends Zax\Components\Collection\PaginatedCollectionControl {

	protected $role;

	protected $aclService;

	protected $permissionService;

	protected $authorizator;

	public function __construct(Model\CMS\Service\AclService $aclService,
								Model\CMS\Service\PermissionService $permissionService,
								Nette\Security\IAuthorizator $authorizator) {
		$this->aclService = $aclService;
		$this->permissionService = $permissionService;
		$this->authorizator = $authorizator;
	}

	public function setRole(Model\CMS\Entity\Role $role) {
		$this->role = $role;
		return $this;
	}

	public function getResultSet() {
		return $this->aclService->fetchQueryObject(new Model\CMS\Query\AclQuery);
	}

    public function viewDefault() {
        
    }
    
    public function beforeRender() {
        $this->template->acl = $this->getPaginator()->getFilteredResultSet();//$this->aclService->findBy(['role' => $this->role]);
	    $this->template->permissions = $this->permissionService->fetchQueryObject(new Model\CMS\Query\PermissionQuery($this->getLocale()));
	    $this->template->authorizator = $this->authorizator;
	    $this->template->role = $this->role;
    }

}