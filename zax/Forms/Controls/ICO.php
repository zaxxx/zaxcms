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
 * Class ICOInput
 *
 * TODO: Form controls are outdated
 *
 * @package Zax\Forms\Controls
 */
class ICOInput extends BaseControl {

    protected $code, $ico;



    public function __construct($label = NULL) {
        parent::__construct($label);
        $this->addCondition(array($this, 'validateFilled'))
            ->addRule(array($this, 'validateData'), 'Toto není platné IČO.');
    }

    public function setValue($value) {
        if($value === NULL) {
            $this->code = $this->ico = NULL;
        }else if(!is_int(substr($value, 0, 2))) {
            $this->code = strtoupper(substr($value, 0, 2));
            $this->ico = substr($value, 2);
        } else {
            $this->code = $value;
        }
        return $this;
    }

    public function getValue() {
        return (empty($this->code) ? $this->code : '') . $this->ico;
    }

    public function loadHttpData() {
        $this->code = $this->getHttpData(Form::DATA_LINE, '[code]');
        $this->ico = $this->getHttpData(Form::DATA_LINE, '[ico]');
    }

    public function getControl() {
        $this->setOption('rendered', TRUE);

        $name = $this->getHtmlName();

        return Html::el('div')->class('ico_input')->setHtml(
            Html::el('input')->type('text')->name($name . '[code]')->maxLength(2)->value($this->code) . ' - '
            . Html::el('input')->type('text')->name($name . '[ico]')->maxLength(8)->value($this->ico)
        );
    }

    protected function isIcoValid($ico) {
        $icoLen = strlen($ico);
        if($icoLen < 8) {
            $ico = str_repeat('0', 8-$icoLen) . $ico;
        }

        if(!preg_match('#^\d{8}$#', $ico)) {
            return FALSE;
        }

        $a = 0;
        for($i=0; $i<7; $i++) {
            $a += $ico[$i] * (8-$i);
        }

        $a = $a % 11;

        $c = 0;
        if($a === 0 || $a === 10)
            $c = 1;
        else if($a === 1)
            $c = 0;
        else
            $c = 11 - $a;

        return (int) $ico[7] === $c;
    }

    public static function validateFilled(Nette\Forms\IControl $control) {
        return !empty($control->ico);
    }

    public static function validateData(Nette\Forms\IControl $control) {
        return (strlen($control->code) && !is_int($control->code)) && $control->isIcoValid($control->ico);
    }

}
 