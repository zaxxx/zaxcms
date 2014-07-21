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
 * Class DateTimeInput
 *
 * TODO: Form controls are outdated
 *
 * @package Zax\Forms\Controls
 */
class OldDateTimeInput extends DateInput {

	protected $hour, $minute;

	public function __construct($label = NULL) {
		parent::__construct($label);
	}

	public function setValue($value) {
		if($value) {
			$date = Nette\Utils\DateTime::from($value);
			$this->hour = $date->format('G');
			$this->minute = $date->format('i');
		} else {
			$this->hour = $this->minute = NULL;
		}
		parent::setValue($value);
		return $this;
	}

	protected function validTime($h, $m) {
		return ($h !== NULL && $m !== NULL) && (($h > -1 && $h < 24) && ($m > -1 && $m < 60));
	}

	public function getValue() {
		return (checkdate((int)$this->month, (int)$this->day, (int)$this->year) && $this->validTime($this->hour, $this->minute))
			? Nette\Utils\DateTime::from($this->year . '-' . $this->month . '-' . $this->day . ' ' . ($this->hour == NULL ? '00' : $this->hour) . ':' . ($this->minute == NULL ? '00' : $this->minute) . ':00')
			: NULL;
	}

	public function loadHttpData() {
		$this->hour = $this->getHttpData(Form::DATA_LINE, '[hour]');
		$this->minute = $this->getHttpData(Form::DATA_LINE, '[minute]');
		if($this->hour === NULL) {
			$this->hour = 0;
		}
		if($this->minute === NULL) {
			$this->minute = 0;
		}
		parent::loadHttpData();
	}

	public function getControl() {
		$this->setOption('rendered', TRUE);

		$name = $this->getHtmlName();

		$day = Html::el('input')->type('number')->title('Den')->placeholder('den')->min(1)->max(31)->name($name . '[day]')->value($this->day);
		$month = Helpers::createSelectBox(
				$this->months,
				array('selected?' => ($this->month === NULL ? '' : $this->month))
			)->name($name . '[month]')->title('Měsíc');
		$year = Html::el('input')->type('number')->min(0)->max(3000)->title('Rok')->placeholder('rok')->name($name . '[year]')->value($this->year);
		$hour = Html::el('input')->type('number')->min(0)->max(23)->title('Hodina')->placeholder('h')->name($name . '[hour]')->value($this->hour);
		$minute = Html::el('input')->type('number')->min(0)->max(59)->title('Minuta')->placeholder('m')->name($name . '[minute]')->value($this->minute);
		if($this->bootstrap) {
			$day->addClass('form-control');
			$month->addClass('form-control');
			$year->addClass('form-control');
			$hour->addClass('form-control');
			$minute->addClass('form-control');
			return Html::el('div')->class('row date_input')->setHtml(
				  Html::el('div')->class('col-sm-1')->setHtml($day)
				. Html::el('div')->class('col-sm-2')->setHtml($month)
				. Html::el('div')->class('col-sm-2')->setHtml($year)
				. Html::el('div')->class('col-sm-1')->setHtml($hour)
				. Html::el('div')->class('col-sm-1')->setHtml($minute)
			);
		} else
			return Html::el('div')->class('date_input')->setHtml(
			$day . $month . $year . $hour . $minute);
	}

	public static function validateFilled(Nette\Forms\IControl $control) {
		return $control->day != NULL || $control->month != NULL || $control->year != NULL || $control->hour != NULL || $control->minute != NULL;
	}

	public static function validateData(Nette\Forms\IControl $control) {
		return checkdate((int)$control->month, (int)$control->day, (int)$control->year) && $control->validTime($control->hour, $control->minute);
	}

}