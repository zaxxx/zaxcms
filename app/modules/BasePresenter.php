<?php

namespace ZaxCMS;
use Nette,
	Zax,
	ZaxCMS,
	ZaxCMS\Components,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Kdyby;


abstract class BasePresenter extends ZaxUI\Presenter {

	protected $webContentFactory;

	protected $staticLinkerFactory;

	protected $flashMessageFactory;

	protected $navigationFactory;

	protected $tinyLoginBoxFactory;

	protected $localeSelectFactory;

	protected $categoryStrategy;

	protected $pageStrategy;

	protected $presenterStrategy;

	public function injectFlashMessageFactory(Components\FlashMessage\IFlashMessageFactory $flashMessageFactory) {
		$this->flashMessageFactory = $flashMessageFactory;
	}

	public function injectStaticLinkerFactory(Components\StaticLinker\IStaticLinkerFactory $staticLinkerFactory) {
		$this->staticLinkerFactory = $staticLinkerFactory;
	}

	public function injectWebContentFactory(Components\WebContent\IWebContentFactory $webContentFactory) {
		$this->webContentFactory = $webContentFactory;
	}

	public function injectNavigationFactory(Components\Navigation\INavigationFactory $navigationFactory) {
		$this->navigationFactory = $navigationFactory;
	}

	public function injectTinyLoginBoxFactory(Components\Auth\ITinyLoginBoxFactory $tinyLoginBoxFactory) {
		$this->tinyLoginBoxFactory = $tinyLoginBoxFactory;
	}

	public function injectLocaleSelectFactory(Components\LocaleSelect\ILocaleSelectFactory $localeSelectFactory) {
		$this->localeSelectFactory = $localeSelectFactory;
	}

	public function injectMarkNavAsActiveStrategies(Components\Navigation\MarkActiveStrategies\CategoryStrategy $categoryStrategy,
													Components\Navigation\MarkActiveStrategies\PageStrategy $pageStrategy,
													Components\Navigation\MarkActiveStrategies\PresenterStrategy $presenterStrategy) {
		$this->categoryStrategy = $categoryStrategy;
		$this->pageStrategy = $pageStrategy;
		$this->presenterStrategy = $presenterStrategy;
	}

	protected function createComponentLocaleSelect() {
	    return $this->localeSelectFactory->create();
	}

	protected function createComponentTinyLoginBox() {
	    $loginBox = $this->tinyLoginBoxFactory->create()
		    ->enableAjax();
		$loginBox->getComponent('loginForm')->groupLoginPasswordErrors();
		return $loginBox;
	}

	protected function createComponentNavigation() {
		return new NetteUI\Multiplier(function($id) {
	        $nav = $this->navigationFactory->create()
		        ->enableAjax()
		        ->setMenuName($id)
	            ->enableDropdown()
	            ->enableDropdownCaret()
		        ->setBSNavbarClasses();

			$nav->getMarkActiveStrategy()
				->addStrategy($this->categoryStrategy)
				->addStrategy($this->pageStrategy)
				->addStrategy($this->presenterStrategy);

			return $nav;
		});
	}

	protected function createComponentWebContent() {
		return new NetteUI\Multiplier(function($name) {
			return $this->webContentFactory->create()
				->setCacheNamespace('ZaxCMS.WebContent.' . $name)
				->enableAjax()
				->setName($name);
		});
	}

	/**
	 * @return Nette\Application\UI\Multiplier
	 */
	protected function createComponentStaticLinker() {
		return new Nette\Application\UI\Multiplier(function($id) {
			$dir = $this->rootDir . '/pub';
			return $this->staticLinkerFactory->create()
				->setCacheNamespace('Zax.StaticLinker.' . $id)
				->setRoot($this->rootDir)
				->setOutputDirectory($dir . '/combined/' . $id)
				->setMinifier(new Components\StaticLinker\NoMinifier)
				->addCssFiles(Nette\Utils\Finder::findFiles('*.css')->from($dir . '/' . $id . '/css'))
				->addJsFiles(Nette\Utils\Finder::findFiles('*.js')->from($dir . '/' . $id . '/js'));
		});
	}

}