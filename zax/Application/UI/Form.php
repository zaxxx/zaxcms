<?php

namespace Zax\Application\UI;
use Nette,
	Zax;

/**
 * Class Form
 *
 * Enhances Nette Forms with some additional features:
 * - has renderer that can specify wrapper for submit buttons
 * - Bootstrap 3 rendering support
 * - autofocus support, including AJAX
 * - <button> submit with icons
 * - <a href> submit (great for 'cancel' buttons)
 * - Filestyle support on upload input
 * - reset() method... cuz why not?
 *
 * @package Zax\Application\UI
 */
class Form extends Nette\Application\UI\Form {

	/** @var Zax\Forms\Rendering\DefaultFormRenderer */
	private $renderer;

	/** @var string|NULL */
	private $autofocus;

	protected $icons;

	public function setIcons(Zax\Html\Icons\IIcons $icons) {
		$this->icons = $icons;
		return $this;
	}

	/**
	 * Custom renderer factory
	 *
	 * @return Zax\Forms\Rendering\DefaultFormRenderer
	 */
	public function getRenderer() {
		if($this->renderer === NULL) {
			$this->renderer = new Zax\Forms\Rendering\DefaultFormRenderer;
		}
		return $this->renderer;
	}

	/**
	 * Enables autofocus on form input - handles "autofocus" attribute and AJAX
	 *
	 * @param $name
	 * @return $this
	 */
	public function autofocus($name) {
		$this->autofocus = $name;
		return $this;
	}

	/**
	 * Autofocus AJAX handling.
	 *
	 * @param $presenter
	 */
	public function attached($presenter) {
		parent::attached($presenter);
		if($this->autofocus !== NULL) {
			$this[$this->autofocus]->setAttribute('autofocus');
			if($presenter->isAjax()) {
				$presenter->payload->focus = $this[$this->autofocus]->getHtmlId();
			}
		}
	}

	/**
	 * @param array $buttonTypes
	 * @return $this
	 */
	protected function enableBootstrapOnInputs($buttonTypes = []) {
		foreach($this->getControls() as $name => $control) {
				if($control instanceof Nette\Forms\Controls\Button || $control instanceof Zax\Forms\Controls\LinkSubmitButton) {
					$applied = FALSE;
					foreach($buttonTypes as $type => $buttons) {
						if(in_array($name, $buttons)) {
							$control->getControlPrototype()->addClass('btn btn-' . $type);
							$applied = TRUE;
						}
					}
					if(!$applied) {
						$control->getControlPrototype()->addClass('btn');
					}
				} else if ($control instanceof Nette\Forms\Controls\TextBase
					|| $control instanceof Nette\Forms\Controls\SelectBox
					|| $control instanceof Nette\Forms\Controls\MultiSelectBox) {
						$control->getControlPrototype()->addClass('form-control');

				} else if ($control instanceof Nette\Forms\Controls\Checkbox
					|| $control instanceof Nette\Forms\Controls\CheckboxList
					|| $control instanceof Nette\Forms\Controls\RadioList) {
						$control->getSeparatorPrototype()->setName('div')->addClass($control->getControlPrototype()->type);

				}/* else if ($control instanceof Zax\Forms\IControl) { // Just some future plans ;-)
					$control->enableBootstrap();
				}*/
		}
		return $this;
	}

	/**
	 * Bootstrap 3 rendering
	 *
	 * $buttonTypes - array of $type => buttonNames[]
	 * $gridLabelSize - Size of label on scale 1-11 (Bootstrap grid system)
	 * $deviceSize - xs|sm|md|lg
	 * $type - form-horizontal|form-inline
	 */

	/**
	 * Bootstrap 3 rendering
	 *
	 * @param array  $buttonTypes array of [$type => $buttonNames[]], eg. ['primary' => ['submit'], 'default' => ['cancel', 'reset']]
	 * @param bool   $groupSubmits Wrap submits in .btn-group?
	 * @param int    $gridLabelSize Bootstrap column size for labels
	 * @param string $deviceSize When should form collapse?
	 * @param string $type Bootstrap form type, eg. 'form-horizontal', 'form-inline'
	 * @return $this
	 */
	public function enableBootstrap($buttonTypes = [], $groupSubmits = FALSE, $gridLabelSize = 3, $deviceSize = 'sm', $type = 'form-horizontal') {

		// Setup renderer, blah, I want this nasty code somewhere "away" from my projects
		$r = $this->getRenderer();
		$r->wrappers['controls']['container'] = NULL;
		$r->wrappers['pair']['container'] = 'div class="form-group"';
		$r->wrappers['pair']['.error'] = 'has-error';
		if($type === 'navbar-form' || $type === 'form-inline') {
			$r->wrappers['control']['container'] = NULL;
			$r->wrappers['submits']['container'] = 'div' . ($groupSubmits ? ' class=btn-group' : '');
			$r->wrappers['label']['container'] = NULL;
		} else {
			$r->wrappers['control']['container'] = 'div class=col-' . $deviceSize . '-' . (12-$gridLabelSize);
			$r->wrappers['submits']['container'] = 'div class="' . ($groupSubmits ? 'btn-group ' : '') . 'col-' . $deviceSize . '-' . (12-$gridLabelSize) . '"';
			$r->wrappers['label']['container'] = 'div class="col-' . $deviceSize . '-' . ($gridLabelSize) . ' control-label"';
		}
		$r->wrappers['control']['description'] = 'span class=help-block';
		$r->wrappers['control']['errorcontainer'] = 'span class=help-block';
		$r->wrappers['error']['container'] = 'div class=has-error';
		$r->wrappers['error']['item'] = 'div class=help-block';


		$this->getElementPrototype()->addClass($type);
		$this->getElementPrototype()->role = 'form';

		$this->enableBootstrapOnInputs($buttonTypes);

		return $this;
	}

	/**
	 * Submit button using <button> tag for icons
	 *
	 * @param       $name
	 * @param null  $label
	 * @param null  $icon
	 * @param array $data
	 * @return Nette\Forms\Controls\SubmitButton
	 */
	public function addButtonSubmit($name, $label = NULL, $icon = NULL, $data = array()) {
		$control = new Nette\Forms\Controls\SubmitButton($label);
		$proto = $control->getControlPrototype();
		$proto->setName('button');
		$proto->setType('submit');
		foreach($data as $key=>$value) {
			$proto->setData($key, $value);
		}

		$label = $this->makeLabel($this->translator->translate($label), $icon);

		$proto->setHtml($label);
		return $this[$name] = $control;
	}

	/**
	 * Custom file upload factory to support 'filestyle' library.
	 *
	 * @param      $name
	 * @param null $label
	 * @param bool $multiple
	 * @return Nette\Forms\Controls\UploadControl
	 */
	public function addUpload($name, $label = NULL, $multiple = FALSE) {
		$upload = parent::addUpload($name, $label, $multiple);
		$upload->getControlPrototype()->setData(['buttonText' => $this->translator->translate('common.button.chooseFile')]);
		return $upload;
	}

	public function addArrayTextArea($name, $label=NULL) {
		$control = new Zax\Forms\Controls\ArrayTextAreaControl($label);
		return $this[$name] = $control;
	}

	public function addNeonTextArea($name, $label=NULL) {
		$control = new Zax\Forms\Controls\NeonTextAreaControl($label);
		return $this[$name] = $control;
	}

	/**
	 * Label with icon factory.
	 *
	 * @param $label
	 * @param $icon
	 * @return string
	 */
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

	/**
	 * Static read-only control displayed as text (not input)
	 *
	 * @param $name
	 * @param $label
	 * @return Zax\Forms\Controls\StaticControl
	 */
	public function addStatic($name, $label) {
		$control = new Zax\Forms\Controls\StaticControl($label);
		return $this[$name] = $control;
	}

	/**
	 * A link that pretends to be a button. Great for 'cancel' submit buttons in forms with files (because regular
	 * submit will take the time to upload the file before checking that a 'cancel' button has been clicked)
	 *
	 * @param      $name
	 * @param null $label
	 * @param null $icon
	 * @param null $destination
	 * @return Zax\Forms\Controls\LinkSubmitButton
	 */
	public function addLinkSubmit($name, $label = NULL, $icon = NULL, $destination = NULL) {
		$control = new Zax\Forms\Controls\LinkSubmitButton($label);
		$proto = $control->getControlPrototype();
		$proto->href($destination);
		$proto->setHtml($this->makeLabel($this->translator->translate($label), $icon));
		return $this[$name] = $control;
	}

	public function addDateTime($name, $label = NULL, $canBeNull = FALSE) {
		$control = new Zax\Forms\Controls\DateTimeInput($label);
		$control->setCanBeNull($canBeNull);
		return $this[$name] = $control;
	}

	public function addDate($name, $label = NULL, $canBeNull = FALSE) {
		$control = new Zax\Forms\Controls\DateTimeInput($label);
		$control->setCanBeNull($canBeNull);
		$control->setScope($control::SCOPE_DATE);
		return $this[$name] = $control;
	}

	public function addTime($name, $label = NULL, $canBeNull = FALSE) {
		$control = new Zax\Forms\Controls\DateTimeInput($label);
		$control->setCanBeNull($canBeNull);
		$control->setScope($control::SCOPE_TIME);
		return $this[$name] = $control;
	}

	public function addIconSelect($name, $label = NULL) {
		$control = new Zax\Forms\Controls\IconInput($label);
		$control->injectIcons($this->icons);
		return $this[$name] = $control;
	}

	/**
	 * Enables AJAX on this form.
	 *
	 * @return $this
	 */
	public function enableAjax() {
		$this->getElementPrototype()->addClass('ajax');
		foreach($this->getComponents() as $control) {
			if($control instanceof Zax\Forms\Controls\LinkSubmitButton) {
				$control->getControlPrototype()->addClass('ajax');
			}
		}
		return $this;
	}

	/**
	 * Resets form values, good for AJAX.
	 *
	 * @return $this
	 */
	public function reset() {
		 $this->setValues(array(), TRUE);
		 return $this;
	}

}