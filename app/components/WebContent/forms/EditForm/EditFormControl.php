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

	public function viewDefault() {}

	public function beforeRender() {}

	public function setWebContent(Model\WebContent $webContent) {
		$this->webContent = $webContent;
		return $this;
	}

	public function getWebContent() {
		return $this->webContent;
	}

	public function createForm() {
		$f = parent::createForm();

		$this->webContent->setTranslatableLocale($this->parent->getLocale());
		$this->service->refresh($this->webContent);

		$f->addStatic('localeFlag', 'webContent.form.locale')
			->setDefaultValue($this->parent->getLocale());
		$f->addStatic('lastUpdatedstat', 'webContent.form.lastUpdated')
			->addFilter(function($value) {
				return $this->getWebContent()->lastUpdated === NULL
					? Nette\Utils\Html::el('em')->setText($this->translator->translate('common.general.never'))
					: $this->createTemplateHelpers()->beautifulDateTime($this->getWebContent()->lastUpdated);
			});
		$f->addTextArea('content', 'webContent.form.content')
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

		if($this->ajaxEnabled) {
			$f->enableAjax();
		}

		return $f;
	}

	public function formSuccess(Nette\Forms\Form $form, $values) {
		if($form->submitted === $form['editWebContent']) {
			$this->binder->formToEntity($form, $this->webContent);
			$this->service->persist($this->webContent);
			$this->service->flush();
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
	
	protected function createComponentForm() {
		return $this->createForm();
	}

}