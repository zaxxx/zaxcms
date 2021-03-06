<?php

namespace ZaxCMS\Model\CMSInstall;
use Zax,
	ZaxCMS,
	Nette;

class MenuInstaller extends Nette\Object {

	protected $service;

	public function __construct(ZaxCMS\Model\CMS\Service\MenuService $service) {
		$this->service = $service;
	}

	protected function createMenu($name) {
		$menu = $this->service->create();
		$menu->name = $name;
		$menu->text = $name;
		$menu->htmlClass = 'nav navbar-nav';
		$menu->secured = FALSE;
		return $menu;
	}

	protected function createMenuItem($text, $nhref, $icon = NULL) {
		$item = $this->service->create();
		$item->text = $text;
		$item->nhref = $nhref;
		$item->secured = FALSE;
		$item->icon = $icon;
		return $item;
	}

	public function createDefaultMenu() {
		$frontMenu = $this->createMenu('front');
		$this->service->persist($frontMenu);

		$homeItem = $this->createMenuItem('Domů', ':Front:Default:default');
		$homeItem->setTranslatableLocale('cs_CZ');
		$this->service->getRepository()->persistAsLastChildOf($homeItem, $frontMenu);

		$this->service->flush();

		if(in_array('en_US', $this->service->getAvailableLocales())) {
			$homeItem->text = 'Home';
			$homeItem->setTranslatableLocale('en_US');
			$this->service->persist($homeItem);

			$this->service->flush();
		}
	}

	public function createAdminMenu() {
		$adminMenu = $this->createMenu('admin');
		$this->service->persist($adminMenu);

		$dashboardItem = $this->createMenuItem('Panel', ':Admin:Default:default');
		$dashboardItem->setTranslatableLocale('cs_CZ');
		$this->service->getRepository()->persistAsLastChildOf($dashboardItem, $adminMenu);

		$pagesItem = $this->createMenuItem('Stránky', ':Admin:Pages:default', 'file');
		$pagesItem->setTranslatableLocale('cs_CZ');
		$this->service->getRepository()->persistAsLastChildOf($pagesItem, $adminMenu);

		$mailsItem = $this->createMenuItem('E-maily', ':Admin:Mails:default', 'envelope');
		$mailsItem->setTranslatableLocale('cs_CZ');
		$this->service->getRepository()->persistAsLastChildOf($mailsItem, $adminMenu);

		$usersItem = $this->createMenuItem('Uživatelé', ':Admin:Users:default', 'user');
		$usersItem->setTranslatableLocale('cs_CZ');
		$this->service->getRepository()->persistAsLastChildOf($usersItem, $adminMenu);

		$rolesItem = $this->createMenuItem('Role & oprávnění', ':Admin:Roles:default', 'lock');
		$rolesItem->setTranslatableLocale('cs_CZ');
		$this->service->getRepository()->persistAsLastChildOf($rolesItem, $adminMenu);

		$this->service->flush();

		if(in_array('en_US', $this->service->getAvailableLocales())) {
			$dashboardItem->text = 'Dashboard';
			$dashboardItem->setTranslatableLocale('en_US');
			$pagesItem->text = 'Pages';
			$pagesItem->setTranslatableLocale('en_US');
			$mailsItem->text = 'E-mails';
			$mailsItem->setTranslatableLocale('en_US');
			$usersItem->text = 'Users';
			$usersItem->setTranslatableLocale('en_US');
			$rolesItem->text = 'Roles & permissions';
			$rolesItem->setTranslatableLocale('en_US');
			$this->service->persist($dashboardItem);
			$this->service->persist($pagesItem);
			$this->service->persist($mailsItem);
			$this->service->persist($usersItem);
			$this->service->persist($rolesItem);

			$this->service->flush();
		}
	}

}