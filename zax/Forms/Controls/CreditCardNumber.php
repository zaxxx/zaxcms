<?php

namespace Zax\Forms\Controls;
use Nette,
    Nette\Forms\Helpers,
    Nette\Forms\Form,
    Nette\Utils\Html,
    Nette\Forms\Controls\BaseControl;

/**
 * @deprecated
 *
 * Class CreditCardNumberInput
 *
 * TODO: Form controls are outdated
 *
 * @package Zax\Forms\Controls
 */
class CreditCardNumberInput extends BaseControl {

	/**
	 * @var array
	 */
	protected $fields = array();

	/**
	 * @param null $label
	 */
	public function __construct($label = NULL) {
        parent::__construct($label);
        $this->addCondition(array($this, 'validateFilled'))
            ->addRule(array($this, 'validateData'), 'Toto není platné číslo kreditní karty.');
    }

    public function setValue($value) {
        if($value) {
            $this->fields = explode('-', $value);
        } else {
            $this->fields = array();
        }
        return $this;
    }

    public function getValue() {
        return empty($this->fields) ? NULL : implode('-', $this->fields);
    }

    public function loadHttpData() {
        $this->fields = $this->getHttpData(Form::DATA_LINE, '[]');
    }

    public function getControl() {
        $this->setOption('rendered', TRUE);

        $name = $this->getHtmlName();

        return Html::el('div')->class('credit_input')->setHtml(
              Html::el('input')->type('text')->name($name . '[0]')->maxLength(4)->value(isset($this->fields[0]) ? $this->fields[0] : NULL) . ' - '
            . Html::el('input')->type('text')->name($name . '[1]')->maxLength(4)->value(isset($this->fields[1]) ? $this->fields[1] : NULL) . ' - '
            . Html::el('input')->type('text')->name($name . '[2]')->maxLength(4)->value(isset($this->fields[2]) ? $this->fields[2] : NULL) . ' - '
            . Html::el('input')->type('text')->name($name . '[3]')->maxLength(4)->value(isset($this->fields[3]) ? $this->fields[3] : NULL)
        );
    }

    public static function validateFilled(Nette\Forms\IControl $control) {
        return (isset($control->fields[0]) && !empty($control->fields[0]))
            && (isset($control->fields[1]) && !empty($control->fields[1]))
            && (isset($control->fields[2]) && !empty($control->fields[2]))
            && (isset($control->fields[3]) && !empty($control->fields[3]));
    }

    public static function validateData(Nette\Forms\IControl $control) {
        return (isset($control->fields[0]) && is_numeric($control->fields[0]) && strlen($control->fields[0]) === 4)
            && (isset($control->fields[1]) && is_numeric($control->fields[1]) && strlen($control->fields[1]) === 4)
            && (isset($control->fields[2]) && is_numeric($control->fields[2]) && strlen($control->fields[2]) === 4)
            && (isset($control->fields[3]) && is_numeric($control->fields[3]) && strlen($control->fields[3]) === 4);
    }

}
 