<?php

namespace ZaxCMS;
use Nette,
	Zax,
	ZaxCMS,
	ZaxCMS\Components,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	WebLoader,
	Kdyby;


abstract class BasePresenter extends ZaxUI\Presenter {

	use Zax\Traits\TTranslatable,

		Components\FlashMessage\TInjectFlashMessageFactory,
		Components\WebContent\TInjectWebContentFactory,
		Components\Navigation\TInjectNavigationFactory,
		Components\Auth\TInjectTinyLoginBoxFactory,
		Components\Search\TInjectSearchFormFactory,
		Components\LocaleSelect\TInjectLocaleSelectFactory;

	/** @persistent */
	public $locale = 'cs_CZ';

	protected $categoryStrategy;

	protected $pageStrategy;

	protected $presenterStrategy;

	protected $webLoaderFactory;

	public function injectWebLoaderFactory(WebLoader\Nette\LoaderFactory $webLoaderFactory) {
		$this->webLoaderFactory = $webLoaderFactory;
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

	protected function createComponentCss() {
		return new NetteUI\Multiplier(function($id) {
	        return $this->webLoaderFactory->createCssLoader($id);
		});
	}

	protected function createComponentJs() {
		return new NetteUI\Multiplier(function($id) {
			return $this->webLoaderFactory->createJavascriptLoader($id);
		});
	}

	protected function createComponentSearchForm() {
		return $this->searchFormFactory->create();
	}

}