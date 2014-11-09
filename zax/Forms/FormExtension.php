<?php

namespace Zax\Forms;
use Nette,
	Zax,
	Nette\DI,
	Nette\Forms\Container,
	Nette\DI\CompilerExtension;



class FormExtension extends Nette\Object {

	protected $translator;

	protected $icons;

	protected $registered = FALSE;

	public function __construct(Nette\Localization\ITranslator $translator, Zax\Html\Icons\IIcons $icons) {
		$this->translator = $translator;
		$this->icons = $icons;
	}

	protected function makeLabel($label, $icon) {
		if($icon instanceof Nette\Utils\Html) {
			$label = $icon . ' ' . $label;
		} else if(is_string($icon)) {
			$label = $this->icons->getIcon($icon) . ' ' . $label;
		} else if(is_array($icon)) {
			$tmpLabel = '';
			foreach($icon as $icn) {
				$tmpLabel = $tmpLabel . ' ' . $this->icons->getIcon($icn);
			}
			$label = $tmpLabel . ' ' . $label;
		}
		return $label;
	}

	public function register() {

		if($this->registered) {
			return;
		}

		// addLinkSubmit
		Container::extensionMethod('addLinkSubmit', function(Container $container, $name, $label = NULL, $icon = NULL, $destination = NULL) {
			$control = new Zax\Forms\Controls\LinkSubmitButton($label);
			$proto = $control->getControlPrototype();
			$proto->href($destination);
			$proto->setHtml($this->makeLabel($this->translator->translate($label), $icon));
			return $container[$name] = $control;
		});

		// addButtonSubmit
		Container::extensionMethod('addButtonSubmit', function(Container $container, $name, $label = NULL, $icon = NULL, $data = []) {
			$control = new Nette\Forms\Controls\SubmitButton($label);
			$proto = $control->getControlPrototype();
			$proto->setName('button');
			$proto->setType('submit');
			foreach($data as $key=>$value) {
				$proto->setData($key, $value);
			}

			$label = $this->makeLabel($this->translator->translate($label), $icon);

			$proto->setHtml($label);
			return $container[$name] = $control;
		});

		// addTexyArea
		Container::extensionMethod('addTexyArea', function(Container $container, $name, $label = NULL) {
			$control = new Zax\Forms\Controls\TexyAreaInput($label);
			$control->injectIcons($this->icons);
			return $container[$name] = $control;
		});

		// addFileUpload
		Container::extensionMethod('addFileUpload', function(Container $container, $name, $label = NULL, $multiple = FALSE) {
			$upload = new Nette\Forms\Controls\UploadControl($label, $multiple);
			$upload->getControlPrototype()
				->setData([
					'buttonText' => $this->translator->translate('common.button.chooseFile' . ($multiple ? 's' : '')),
					'input' => $multiple ? 'false' : 'true',
					'maxFiles' => $multiple ? Zax\Utils\HttpHelpers::getMaxFileUploads() : 1
				]);
			$upload->addRule(Zax\Application\UI\Form::MAX_FILE_SIZE, NULL, Zax\Utils\HttpHelpers::getMaxUploadSize()*1024*1024);

			return $container[$name] = $upload;
		});

		// addEmail
		Container::extensionMethod('addEmail', function(Container $container, $name, $label = NULL, $cols = NULL, $maxLength = NULL) {
			$text = $container->addText($name, $label, $cols, $maxLength);
			$text->setType('email');
			$text->addCondition(Nette\Forms\Form::FILLED)
				->addRule(Nette\Forms\Form::EMAIL);
			return $text;
		});

		// addArrayTextArea
		Container::extensionMethod('addArrayTextArea', function (Container $container, $name, $label = NULL, $keyValDelimiter = NULL) {
			$control = new Zax\Forms\Controls\ArrayTextAreaControl($label, $keyValDelimiter);
			return $container[$name] = $control;
		});

		// addNeonTextArea
		Container::extensionMethod('addNeonTextArea', function (Container $container, $name, $label = NULL) {
			$control = new Zax\Forms\Controls\NeonTextAreaControl($label);
			return $container[$name] = $control;
		});

		// addStatic
		Container::extensionMethod('addStatic', function (Container $container, $name, $label = NULL) {
			$control = new Zax\Forms\Controls\StaticControl($label);
			return $container[$name] = $control;
		});

		// addAutoComplete
		Container::extensionMethod('addAutoComplete', function (Container $container, $name, $label = NULL, $autocomplete = []) {
			$control = $container->addText($name, $label);
			$control->getControlPrototype()->addClass('jqueryui_autocomplete')
				->setData('autocomplete', $autocomplete);
			return $control;
		});

		// addMultiAutoComplete
		Container::extensionMethod('addMultiAutoComplete', function (Container $container, $name, $label = NULL, $autocomplete = []) {
			$control = $container->addText($name, $label);
			$control->getControlPrototype()->addClass('jqueryui_multiautocomplete')
				->setData('autocomplete', $autocomplete);
			return $control;
		});

		$this->registered = TRUE;

	}

}