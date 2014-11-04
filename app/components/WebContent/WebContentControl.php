<?php

namespace ZaxCMS\Components\WebContent;
use Nette,
	Zax,
	ZaxCMS\Model,
	Zax\Application\UI\SecuredControl;

class WebContentControl extends SecuredControl {

	protected $defaultLinkParams = [
		'edit-localeSelect-locale' => NULL,
		'edit-view' => NULL,
		'edit-fileManager-directoryList-view' => NULL,
		'edit-fileManager-fileList-view' => NULL
	];

	use Zax\Traits\TCacheable;

	protected $webContentService;

	protected $webContent;

	protected $editFactory;

	protected $texyHeadingTop = 3;

	/**
	 * @var string
	 */
	protected $name;

	public function __construct(Model\CMS\Service\WebContentService $webContentService,
								IEditFactory $editFactory) {
		$this->webContentService = $webContentService;
		$this->editFactory = $editFactory;
	}

	public function setTexyHeadingTop($top = 3) {
		$this->texyHeadingTop = $top;
		return $this;
	}

	protected function createTexy() {
		$texy = parent::createTexy();
		$texy->headingModule->top = $this->texyHeadingTop;
		return $texy;
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

	protected function loadWebContent() {
		if($this->webContent === NULL) {
			$webContent = $this->webContentService->getByName($this->name);
			if($webContent === NULL) {
				$webContent = $this->webContentService->createWebContent($this->name);
			}
			$this->setWebContent($webContent);
		}
	}

	public function getWebContent() {
		if($this->webContent === NULL) {
			$this->loadWebContent();
		}

		return $this->webContent;
	}

	public function setWebContent(Model\CMS\Entity\WebContent $webContent) {
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

	public function viewDefault() {
		$texyfied = $this->cache->load('texyfied-' . $this->translator->getLocale());
		if($texyfied === NULL) {
			$this->getWebContent()->setTranslatableLocale($this->getLocale());
			$this->webContentService->refresh($this->getWebContent());
			$this->cache->save(
				'texyfied-' . $this->translator->getLocale(),
				$texyfied = $this->createTexy()->process($this->getWebContent()->content),
				[Nette\Caching\Cache::TAGS => [get_class($this) . '.' . $this->getName()] ]);
		}
		$this->template->cachedOutput = $texyfied;
	}

	/**
	 * @secured WebContent, Use
	 */
	public function viewEdit() {

	}

	public function beforeRender() {

	}

	/**
	 * @secured WebContent, Use
	 */
	protected function createComponentEdit() {
		return $this->editFactory->create()
			->setWebContent($this->getWebContent());
	}



}