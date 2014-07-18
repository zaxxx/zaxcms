<?php

namespace ZaxCMS\Components\WebContent;
use Nette,
    Zax,
	ZaxCMS\Model,
    Zax\Application\UI\Control;

class WebContentControl extends Control {

	use Zax\Traits\TCacheable;

	/**
	 * @var Model\WebContentService
	 */
	protected $webContentService;

	/**
	 * @var Model\WebContent
	 */
	protected $webContent;

	/**
	 * @var IEditFactory
	 */
	protected $editFactory;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @param Model\WebContentService $webContentService
	 * @param IEditFactory            $editFactory
	 */
	public function __construct(Model\WebContentService $webContentService,
	                            IEditFactory $editFactory) {
		$this->webContentService = $webContentService;
		$this->editFactory = $editFactory;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName() {
		if($this->webContent === NULL)
			return $this->name;
		else
			return $this->webContent->name;
	}

	/**
	 * @return null|Model\WebContent
	 */
	public function getWebContent() {
		if($this->webContent === NULL) {
			$webContent = $this->webContentService->getDao()->findOneByName($this->name);
			if($webContent === NULL && $this->canEditWebContent()) {
				$webContent = $this->webContentService->createWebContent($this->name);
			}
			$webContent->setTranslatableLocale($this->translator->getLocale());
			$this->setWebContent($webContent);
		}
		return $this->webContent;
	}

	/**
	 * @param Model\WebContent $webContent
	 * @return $this
	 */
	public function setWebContent(Model\WebContent $webContent) {
		$this->webContent = $webContent;
		return $this;
	}

	/**
	 * @return $this
	 */
	public function invalidateCache() {
		$this->cache->clean([Nette\Caching\Cache::TAGS => [get_class($this) . '.' . $this->getName()] ]);
		return $this;
	}

	/**
	 * @return bool
	 */
	public function canEditWebContent() {
		return TRUE;
	}

	public function viewDefault() {
	    $texyfied = $this->cache->load('texyfied-' . $this->translator->getLocale());
	    if($texyfied === NULL) {
		    $this->webContentService->getEm()->refresh($this->getWebContent());
		    $this->cache->save(
			    'texyfied-' . $this->translator->getLocale(),
			    $texyfied = $this->createTexy()->process($this->getWebContent()->content),
		        [Nette\Caching\Cache::TAGS => [get_class($this) . '.' . $this->getName()] ]);
	    }
	    $this->template->cachedOutput = $texyfied;
    }

	public function viewEdit() {

	}

	public function beforeRender() {
    }

	/**
	 * @return EditControl|NULL
	 */
	protected function createComponentEdit() {
		if($this->canEditWebContent()) {
		    $c = $this->editFactory->create()->setWebContent($this->getWebContent());
			if($this->ajaxEnabled) {
				$c->enableAjax(!$this->autoAjax);
			}
			return $c;
		}
	}



}