<?php

namespace Zax\Application\UI;
use Nette,
	Nette\Utils\Strings,
	Zax,
	Kdyby;

/**
 * Class Control
 *
 * Greatly enhances Nette Control.
 * - translated using Kdyby/Translation
 * - no need to manually redraw snippets
 * - no need to manually specify paths to templates
 * - no need to manually check "if is ajax" everywhere...
 * - has factory for translated forms
 * - has Texy factory
 * - support for custom snippet IDs (Nette default ones give themselves out alot in HTML output and look not so cool)
 *
 * @package Zax\Application\UI
 */
abstract class Control extends Nette\Application\UI\Control {

	use Zax\Traits\TTranslatable;

	/** @persistent */
	public $view = 'Default';

	/** @var string */
	protected $locale;

	/** @var bool */
	protected $ajaxEnabled = FALSE;

	/** @var bool */
	protected $autoAjax = FALSE;

	/** @var array */
	protected $ajaxDisabledFor = [];

	/** @var SnippetGenerators\ISnippetGenerator */
	protected $snippetGenerator;

	/**
	 * Sends flash messages to presenter.
	 *
	 * @param        $message
	 * @param string $type
	 * @return void
	 */
	public function flashMessage($message, $type='info') {
		$this->presenter->flashMessage($message, $type);
	}

	/**
	 * Enables AJAX for this component AND all sub-components
	 *
	 * @param bool $autoAjax Should call redrawControl() in attached()?
	 * @param array $exclude array of subcomponent names which should be excluded from AJAXification
	 * @return $this
	 */
	public function enableAjax($autoAjax = TRUE, $exclude = NULL) {
		$this->ajaxEnabled = TRUE;
		$this->autoAjax = $autoAjax;
		if(is_array($exclude)) {
			$this->disableAjaxFor($exclude);
		}
		return $this;
	}

	/**
	 * Forces AJAX off on specified subcomponents (has higher priority than enableAjax())
	 *
	 * @param array $subcomponents array of subcomponent names which should be excluded from AJAXification
	 * @return $this
	 */
	public function disableAjaxFor($subcomponents = []) {
		$this->ajaxDisabledFor = $subcomponents;
		return $this;
	}

	/**
	 * Disables AJAX for this component.
	 * Do not call in factory, it won't work, use disableAjaxFor in parent component instead ;-)
	 *
	 * @return $this
	 */
	public function disableAjax() {
		$this->ajaxEnabled = FALSE;
		$this->autoAjax = FALSE;
		return $this;
	}

	public function isAjaxEnabled() {
		return $this->ajaxEnabled;
	}

	/**
	 * Does this control have a persistent property $property?
	 *
	 * @param $property
	 * @return bool
	 */
	public function hasPersistentProperty($property) {
		$ref = $this->getReflection();
		if($ref->hasProperty($property)) {
			$refp = $ref->getProperty($property);
			return $refp->isPublic() && $refp->hasAnnotation('persistent');
		}
		return FALSE;
	}

	/**
	 * Forward using $presenter->forward()
	 *
	 * @param       $destination
	 * @param array $args
	 */
	public function presenterForward($destination, $args = []) {
		$name = $this->getUniqueId();
		if($destination != 'this') {
			$destination =  "$name-$destination";
		}
		$params = [];
		foreach($args as $key => $val) {
			$params["$name-$key"] = $val;
		}
		$this->presenter->forward($destination, $params);
	}

	/**
	 * Custom hacky forward, is 'good enough' and additionaly sends anchor in payload
	 *
	 * @param       $destination
	 * @param array $args
	 */
	public function forward($destination, $args = []) {

		// Remove '!' from destination
		$destination = str_replace('!', '', $destination);

		// Remove anchor from destination and insert anchor to payload
		$anchor = strpos($destination, '#');
		if(is_int($anchor)) {
			list($destination, $anchor) = explode('#', $destination);
			if($this->ajaxEnabled && $this->presenter->isAjax()) {
				$this->presenter->payload->anchor = $anchor;
			}
		}
		$this->presenter->payload->setUrl = $this->link($destination, $args);

		// Process arguments
		$params = [];
		foreach($args as $key =>$param) {
			$control = $this;
			$property = $key;

			// Get subcomponent from name
			if(strpos($key, self::NAME_SEPARATOR) > 0) {
				$names = explode(self::NAME_SEPARATOR, $key);
				$property = array_pop($names);
				$control = $this->getComponent(implode(self::NAME_SEPARATOR, $names));
			}

			if($property == 'view') {
				$control->setView($param);
			}else if($control->hasPersistentProperty($property)) {
				$control->$property = $param;
			} else {
				$params[$key] = $param;
			}
		}
		$this->params = $params;

		// No signal
		if($destination == 'this')
			return;

		$this->signalReceived($destination);
	}

	/**
	 * If AJAX forward, else redirect
	 *
	 * @param       $destination
	 * @param array $args
	 * @param array $snippets
	 * @param bool  $presenterForward Prefer $presenter->forward() over $this->forward()
	 */
	final public function go($destination, $args = [], $snippets = [], $presenterForward = FALSE) {
		if($this->ajaxEnabled && $this->presenter->isAjax()) {
			foreach($snippets as $snippet) {
				$this->redrawControl($snippet);
			}

			if($presenterForward) {
				$this->presenterForward($destination, $args);
			} else {
				$this->forward($destination, $args);
			}
		} else {
			$this->redirect($destination, $args);
		}
	}

	/**
	 * @param $view
	 * @return string
	 */
	static public function formatViewMethod($view) {
		return 'view' . Strings::firstUpper($view);
	}

	/**
	 * @param $render
	 * @return string
	 */
	static public function formatBeforeRenderMethod($render) {
		return 'beforeRender' . Strings::firstUpper($render);
	}

	/**
	 * @param $view
	 */
	public function setView($view) {
		$this->view = Strings::firstUpper($view);
	}

	/**
	 * Throws exception if view name contains anything else than alphanumeric characters.
	 *
	 * @param $view
	 * @throws Nette\Application\UI\BadSignalException
	 */
	protected function checkView($view) {
		if(!preg_match('/^([a-zA-Z0-9]+)$/', $view)) {
			throw new Nette\Application\UI\BadSignalException("Signal or view name must be alphanumeric.");
		}
	}

	/**
	 * Automatic snippet invalidation
	 */
	public function attached($presenter) {
		parent::attached($presenter);
		if($this->translator === NULL) {
			$this->translator = $presenter->translator;
		}
		if($this->autoAjax && $this->ajaxEnabled && $presenter->isAjax()) {
			$this->redrawControl();
			$this->presenter->payload->setUrl = $this->link('this');
		}
	}

	/**
	 * Descendants can override this method to customize templates hierarchy
	 *
	 * @param $view
	 * @param $render
	 * @return string
	 */
	public function getTemplatePath($view, $render = '') {
		return dirname($this->reflection->fileName) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $view . (strlen($render) > 0 ? '.' . $render : '') . '.latte';
	}

	/**
	 * Template helpers factory
	 *
	 * @return Zax\Latte\Helpers
	 */
	protected function createTemplateHelpers() {
		$helpers = new Zax\Latte\Helpers;
		$helpers->setTranslator($this->getTranslator());
		return $helpers;
	}

	/* TODO: some stuff for Texy ;-)
	 public function netteLinksHandler($invocation, $phrase, $content, $modifier, $link) {
		if (!$link) return $invocation->proceed();

		if(strpos($link->URL, 'link:') === 0) {
			$linkTmp = substr($link->URL, 5);
			$link->URL = $this->getPresenter(TRUE)->link($linkTmp);
		}

		return $invocation->proceed();
	}*/

	/**
	 * Texy factory
	 *
	 * @return \Texy
	 */
	protected function createTexy() {
		$texy = new \Texy;
		$texy->encoding = 'utf-8';
		$texy->setOutputMode(\Texy::HTML5);
		$texy->headingModule->top = 3;
		return $texy;
	}

	public function getTranslator() {
		return $this->translator === NULL ? $this->presenter->translator : $this->translator;
	}

	/**
	 * Template factory
	 *
	 * @return Nette\Application\UI\ITemplate
	 */
	public function createTemplate() {
		$this->checkView($this->view);
		$template = parent::createTemplate();
		$template->setTranslator($this->getTranslator());
		$template->currentLocale = $this->getLocale();
		$template->availableLocales = $this->getAvailableLocales();
		$helpers = $this->createTemplateHelpers();
		$template->getLatte()->addFilter(NULL, [$helpers, 'loader']);
		$texy = $this->createTexy();
		$template->getLatte()->addFilter('texy', [$texy, 'process']);
		$template->ajaxEnabled = $this->ajaxEnabled;
		$template->view = $this->view;
		return $template;
	}

	protected function createComponent($name) {
		$control = parent::createComponent($name);
		if($this->ajaxEnabled && !in_array($name, $this->ajaxDisabledFor) && ($control instanceof Control || $control instanceof Form)) {
			$control->enableAjax();
		}
		return $control;
	}

	/**
	 * replacement for render(), descendants should override this method
	 */
	protected function beforeRender() {}

	/**
	 * Life cycle
	 *
	 * @param string $render
	 * @throws \Nette\Application\UI\BadSignalException
	 */
	final public function run($render = '') {
		$template = $this->getTemplate();
		$template->setFile($this->getTemplatePath($this->view, $render));

		if(!$this->tryCall($this->formatViewMethod($this->view), $this->params)) {
			$class = get_class($this);
			throw new Nette\Application\UI\BadSignalException("There is no handler for view '$this->view' in class $class.");
		}

		if(!$this->tryCall($this->formatBeforeRenderMethod($render), array())) {
			$class = get_class($this);
			throw new Nette\Application\UI\BadSignalException("There is no 'beforeRender$render' method in class $class.");
		}

		$template->render();
	}

	/**
	 * @param SnippetGenerators\ISnippetGenerator $snippetGenerator
	 * @return $this
	 */
	public function injectSnippetGenerator(SnippetGenerators\ISnippetGenerator $snippetGenerator) {
		$this->snippetGenerator = $snippetGenerator;
		return $this;
	}

	/**
	 * Custom snippet format
	 */
	public function getSnippetId($name = NULL) {
		if($this->snippetGenerator === NULL) {
			return parent::getSnippetId($name);
		}
		return $this->snippetGenerator->getSnippetId($this, $name);
	}

	/**
	 * render method hook
	 *
	 * @param       $func
	 * @param array $args
	 * @return mixed|void
	 */
	public function __call($func, $args = []) {
		if (Strings::startsWith($func, 'render')) {
			return $this->run(Strings::substring($func, 6));
		}
		return parent::__call($func, $args);
	}

}
