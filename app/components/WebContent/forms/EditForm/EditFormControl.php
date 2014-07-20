<?php

namespace ZaxCMS\Components\WebContent;
use Nette,
    Zax,
    ZaxCMS\Model,
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

	public function createForm() {
		$f = parent::createForm();

		$this->webContent->setTranslatableLocale($this->getLocale());
		$this->service->getEm()->refresh($this->webContent);

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

		return $f;
	}

	public function formSuccess(Nette\Forms\Form $form, $values) {

	}

	public function formError(Nette\Forms\Form $form) {

	}
	
	protected function createComponentForm() {
	    return $this->createForm();
	}

}