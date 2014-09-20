<?php

namespace ZaxCMS\Components\WebContent;
use Nette,
	Zax,
	ZaxCMS\Model,
	Doctrine,
	Kdyby,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Zax\Application\UI\FormControl;

class EditFormControl extends FormControl {

	protected $webContent;

	protected $webContentService;

	public function __construct(Model\CMS\Service\WebContentService $webContentService) {
		$this->webContentService = $webContentService;
	}

	/**
	 * @secured WebContent, Edit
	 */
	public function viewDefault() {}

	public function beforeRender() {}

	public function setWebContent(Model\CMS\Entity\WebContent $webContent) {
		$this->webContent = $webContent;
		return $this;
	}

	public function getWebContent() {
		return $this->webContent;
	}

	public function createForm() {
		$f = parent::createForm();

		$this->webContent->setTranslatableLocale($this->parent->getLocale());
		$this->webContentService->refresh($this->webContent);

		$f->addStatic('localeFlag', 'webContent.form.locale')
			->setDefaultValue($this->parent->getLocale())
			->addFilter(function($locale) {
				return $this->translator->translate('common.lang.' . $locale);
			});
		$f->addStatic('lastUpdatedstat', 'webContent.form.lastUpdated')
			->addFilter(function($value) {
				return $this->getWebContent()->lastUpdated === NULL
					? Nette\Utils\Html::el('em')->setText($this->translator->translate('common.general.never'))
					: $this->presenter->getTemplateFactory()->createTemplateHelpers($this->translator)->beautifulDateTime($this->getWebContent()->lastUpdated); // dirty huehue
			});
		$f->addTexyArea('content', 'webContent.form.content')
			->getControlPrototype()->rows(10);

		$f->addHidden('locale', $this->getLocale());

		$this->binder->entityToForm($this->webContent, $f);

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

		//$f->autofocus('content');

		return $f;
	}

	public function formSuccess(Nette\Forms\Form $form, $values) {
		if($form->submitted === $form['editWebContent']) {
			$this->webContent->setTranslatableLocale($values->locale);
			$this->binder->formToEntity($form, $this->webContent);
			$this->webContentService->persist($this->webContent);
			$this->webContentService->flush();
			$this->lookup('ZaxCMS\Components\WebContent\WebContentControl')->invalidateCache();
			$this->flashMessage('common.alert.changesSaved', 'success');
			$this->parent->go('this');
		} else if($form->submitted === $form['previewWebContent']) {
			$this->binder->formToEntity($form, $this->webContent);
			$this->parent->redrawControl('preview');
		}
	}

	public function formError(Nette\Forms\Form $form) {
		$this->flashMessage('common.alert.changesError', 'error');
	}

	public function handleCancel() {
		$this->parent->close();
	}

}