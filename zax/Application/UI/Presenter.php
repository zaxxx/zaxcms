<?php

namespace Zax\Application\UI;
use Nette,
	Nextras,
	Zax,
	Kdyby;

/**
 * Class Presenter
 *
 * Adds some often needed features:
 * - ready for localizations
 * - factories for flash message and static linker components
 *
 * @package Zax\Application\UI
 */
abstract class Presenter extends Nette\Application\UI\Presenter {

	use Nextras\Application\UI\SecuredLinksPresenterTrait;

	/** @var bool */
	protected $ajaxEnabled = FALSE;

	/**
	 * @var Zax\Utils\RootDir
	 */
	protected $rootDir;

	public function injectRootDir(Zax\Utils\RootDir $rootDir) {
		$this->rootDir = $rootDir;
	}

	/**
	 * Additionally redraws snippet with flash messages.
	 *
	 * @param        $message
	 * @param string $type
	 * @return \stdClass|void
	 */
	public function flashMessage($message, $type = 'info') {
		parent::flashMessage($message, $type);
		$this['flashMessage']->redrawControl();
	}

	/**
	 * @return Zax\Components\FlashMessage\FlashMessageControl
	 */
	protected function createComponentFlashMessage() {
		return $this->flashMessageFactory->create()
			->setFlashes($this->getTemplate()->flashes)
			->enableGlyphicons();
	}



	/**
	 * If AJAX forward, else redirect
	 *
	 * @param       $destination
	 * @param array $args
	 * @param array $snippets
	 */
	final public function go($destination, $args = [], $snippets = []) {
		if($this->ajaxEnabled && $this->presenter->isAjax()) {
			foreach($snippets as $snippet) {
				$this->redrawControl($snippet);
			}
			$this->forward($destination, $args);
		} else {
			$this->redirect($destination, $args);
		}
	}

	public function getTranslator() {
		return $this->translator;
	}

}