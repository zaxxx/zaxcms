<?php

namespace ZaxCMS\Model\CMSInstall;
use Zax,
	ZaxCMS,
	Nette;

class RoleInstaller extends Nette\Object {

	protected $service;

	public function __construct(ZaxCMS\Model\CMS\Service\RoleService $service) {
		$this->service = $service;
	}

	protected function createRole($name, $displayName, $description = NULL, $special = NULL) {
		$role = $this->service->create();
		$role->name = $name;
		$role->displayName = $displayName;
		$role->description = $description;
		$role->special = $special;
		return $role;
	}

	public function createDefaultRoles() {

		$guest = $this->createRole('guest', 'Návštěvník', 'Tato role je automaticky přiřazena všem nepřihlášeným návštěvníkům.', ZaxCMS\Model\CMS\Entity\Role::GUEST_ROLE);
		$guest->setTranslatableLocale('cs_CZ');
		$this->service->persist($guest);

		$user = $this->createRole('user', 'Uživatel', 'Toto je základní role pro přihlášené uživatele.', ZaxCMS\Model\CMS\Entity\Role::USER_ROLE);
		$user->parent = $guest;
		$user->setTranslatableLocale('cs_CZ');
		$this->service->persist($user);

		$superadmin = $this->createRole('superadmin', 'Superadmin', 'Superadmin má neomezenou moc', ZaxCMS\Model\CMS\Entity\Role::ADMIN_ROLE);
		$superadmin->parent = $user;
		$superadmin->setTranslatableLocale('cs_CZ');
		$this->service->persist($superadmin);

		$this->service->flush();

		if(in_array('en_US', $this->service->getAvailableLocales())) {
			$guest->displayName = 'Visitor';
			$guest->description = 'This role represents anonymous visitors.';
			$guest->setTranslatableLocale('en_US');
			$this->service->persist($guest);

			$user->displayName = 'User';
			$user->description = 'This is a basic role for authenticated user.';
			$user->setTranslatableLocale('en_US');
			$this->service->persist($user);

			$superadmin->displayName = 'Superadmin';
			$superadmin->description = 'Superadmin has unlimited powers';
			$superadmin->setTranslatableLocale('en_US');
			$this->service->persist($superadmin);

			$this->service->flush();
		}
	}

}