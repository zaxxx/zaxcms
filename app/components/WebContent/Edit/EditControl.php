<?php

namespace ZaxCMS\Components\WebContent;
use Nette,
    Zax,
	ZaxCMS\Model,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class EditControl extends Control {

	/**
	 * @var Model\WebContent
	 */
	protected $webContent;

	/**
	 * @var Model\WebContentService
	 */
	protected $webContentService;

	/**
	 * @var Zax\Components\FileManager\IFileManagerFactory
	 */
	protected $fileManagerFactory;

	/**
	 * @var Zax\Utils\RootDir
	 */
	protected $rootDir;

	/** @persistent */
	public $locale;

	/**
	 * @param Model\WebContentService                        $webContentService
	 * @param Zax\Components\FileManager\IFileManagerFactory $fileManagerFactory
	 * @param Zax\Utils\RootDir                              $rootDir
	 */
	public function __construct(Model\WebContentService $webContentService,
	                            Zax\Components\FileManager\IFileManagerFactory $fileManagerFactory,
	                            Zax\Utils\RootDir $rootDir) {
		$this->webContentService = $webContentService;
		$this->fileManagerFactory = $fileManagerFactory;
		$this->rootDir = $rootDir;
	}

	/**
	 * @param Model\WebContent $webContent
	 * @return $this
	 */
	public function setWebContent(Model\WebContent $webContent) {
		$this->webContent = $webContent;
		return $this;
	}

    public function viewDefault() {
        
    }
	
	public function viewFiles() {
	    
	}
    
    public function beforeRender() {
		$this->template->availableLocales = $this->getAvailableLocales();
	    $this->template->currentLocale = $this->getLocale();
	    $this->template->webContent = $this->webContent;
    }

	public function handleClose() {
		$this->close();
	}

	public function close() {
		$this->webContent->setTranslatableLocale($this->translator->locale);
		$this->webContentService->getEm()->refresh($this->webContent);
		$this->parent->go('this', ['view' => 'Default', 'edit-locale' => NULL, 'edit-view' => 'Default']);
	}

	public function handleCancel() {
		$this->close();
	}

	/**
	 * @return ZaxUI\Form
	 */
	protected function createComponentEditForm() {
		$f = $this->createForm();

		$this->webContent->setTranslatableLocale($this->getLocale());
		$this->webContentService->getEm()->refresh($this->webContent);

		$f->addStatic('localeFlag', 'webContent.form.locale')
			->setDefaultValue($this->getLocale());
		$f->addDateTime('lastUpdated', 'webContent.form.lastUpdated', TRUE)
			->setDefaultValue($this->webContent->lastUpdated);
		$f->addStatic('lastUpdated2', 'webContent.form.lastUpdated')
			->setDefaultValue($this->webContent->lastUpdated === NULL ? Nette\Utils\Html::el('em')->setText($this->translator->translate('common.general.never')) : $this->createTemplateHelpers()->beautifulDateTime($this->webContent->lastUpdated));
		$f->addTextArea('content', 'webContent.form.content')
			->setDefaultValue($this->webContent->content)
			->getControlPrototype()->rows(10);
		$f->addHidden('locale', $this->getLocale());

		$f->addProtection();

		$f->addButtonSubmit('editWebContent', 'common.button.saveChanges', 'pencil');
		$f->addButtonSubmit('previewWebContent', 'common.button.preview', 'search');
		$f->addLinkSubmit('cancel', 'common.button.close', 'remove', $this->link('cancel!'));

		$f->addStatic('note', '')
			->setDefaultValue($this->translator->translate('webContent.panel.previewIsBelow'));

		$f->enableBootstrap([
			'success' => ['editWebContent'],
			'primary' => ['previewWebContent'],
			'default' => ['cancel']
		], TRUE);

		if($this->ajaxEnabled) {
			$f->enableAjax();
		}

		$f->onSuccess[] = function(ZaxUI\Form $form, $values) {
			$this->webContent->content = $values->content;
			$this->webContent->lastUpdated = $values->lastUpdated;
			$this->webContent->setTranslatableLocale($values->locale);

			if($form->submitted === $form['editWebContent']) {
				//$this->webContent->lastUpdated = new \DateTime;
				$this->webContentService->getEm()->persist($this->webContent);
				$this->webContentService->getEm()->flush();
				$this->webContentService->getEm()->refresh($this->webContent);
				$this->parent->invalidateCache();
				$this->redrawControl();
				$this->flashMessage('common.alert.changesSaved', 'success');
			} else if($form->submitted === $form['previewWebContent']) {
				$this->parent->redrawControl(NULL, FALSE);
				$this->redrawControl('preview');
			}

			$this->go('this', []);
		};

		$f->onError[] = function() {
			$this->flashMessage('common.alert.changesError', 'danger');
		};

		return $f;
	}

	/**
	 * @return Zax\Components\FileManager\FileManagerControl
	 */
	protected function createComponentFileManager() {
		return $this->fileManagerFactory->create()
			->setRoot($this->rootDir . '/upload/webContent/' . $this->webContent->name)
			->enableAjax($this->autoAjax)
			->enableFeatures(
				[
					'createDir',
					'renameDir',
					'deleteDir',
					'truncateDir',
					'uploadFile',
					'renameFile',
					'deleteFile',
					'linkFile'
				]
			);
	}

}