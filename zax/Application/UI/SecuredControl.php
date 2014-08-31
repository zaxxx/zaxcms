<?php

namespace Zax\Application\UI;
use Nette,
	Nextras,
	Zax,
	Latte,
	Zax\Application\UI;

abstract class SecuredControl extends UI\Control {

	use Nextras\Application\UI\SecuredLinksControlTrait;

	const CCMODE_EXCEPTION = 0, // Throw exception if user isn't allowed to create component
		  CCMODE_SILENT = 1;    // Return empty component if user isn't allowed to create component

	protected $createComponentMode = self::CCMODE_SILENT;

	/** @var Zax\Security\IPermission */
	protected $permission;

	/** @var Nette\Security\User */
	protected $user;

	protected $emptyControlFactory;

	public function injectUser(Nette\Security\User $user) {
		$this->user = $user;
	}

	public function injectEmptyControlFactory(IEmptyFactory $emptyControlFactory) {
		$this->emptyControlFactory = $emptyControlFactory;
	}

	public function injectDefaultPermission(Zax\Security\DefaultPermission $permission) {
		$this->permission = $permission;
	}

	public function templatePrepareFilters($template) {
		parent::templatePrepareFilters($template);

		$latte = $template->getLatte();
		$lattePermission = new Zax\Security\LattePermission($this->permission);
		$lattePermission->install($latte);
	}

	public function createTemplate() {
		$template = parent::createTemplate();
		$template->acl = $this->permission;
		return $template;
	}

	public function checkRequirements($element, $params = []) {
		$this->permission->checkAnnotationRequirements($element, $params);
	}

	/**
	 * @hack
	 */
	protected function tryCall($method, array $params) {
		$rc = $this->getReflection();
		if($rc->hasCallableMethod($method)) {
			$rm = $rc->getMethod($method);
			$this->checkRequirements($rm, $params);
			$rm->invokeArgs($this, $rc->combineArgs($rm, $params));
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * @hack
	 */
	protected function createComponent($name) {
		$ucName = ucfirst($name);
		$method = 'createComponent' . $ucName;

		if(method_exists($this, $method)) {
			if($this->createComponentMode === self::CCMODE_SILENT) {
				try {
					$this->checkRequirements($this->getReflection()->getMethod($method));
				} catch (Nette\Application\ForbiddenRequestException $ex) {
					return $this->emptyControlFactory->create();
				}
			} else {
				$this->checkRequirements($this->getReflection()->getMethod($method));
			}
		}

		return parent::createComponent($name);
	}

}