<?php

namespace Zax\Forms\Controls;
use Nette,
	Nette\Forms\Helpers,
	Nette\Forms\Form,
	Nette\Utils\Html;

/**
 * Class DateTimeInput
 *
 * @package Zax\Forms\Controls
 */
class DateTimeInput extends BaseControl {

	/** @persistent */
	public $selectedValue;

	/** @persistent */
	public $setNull;

	const SCOPE_DATE = 1,
		  SCOPE_TIME = 2,
		  SCOPE_DATETIME = 3;

	protected $year;

	protected $month;

	protected $day;

	protected $hour;

	protected $minute;

	protected $scope = self::SCOPE_DATETIME;

	protected $canBeNull = FALSE;

	public function __construct($label = NULL) {
		parent::__construct($label);
	}

	public function setScope($scope = self::SCOPE_DATETIME) {
		$this->scope = min(3, max(1, (int)$scope));
		return $this;
	}

	public function setCanBeNull($can = TRUE) {
		$this->canBeNull = (bool)$can;
		return $this;
	}

	public function setValue($value) {
		if($value) {
			$date = Nette\Utils\DateTime::from($value);
			$this->year = $date->format('Y');
			$this->month = $date->format('n');
			$this->day = $date->format('j');
			$this->hour = $date->format('G');
			$this->minute = $date->format('i');
		} else {
			$this->year = $this->month = $this->day = $this->hour = $this->minute = NULL;
		}
		parent::setValue($value);
		return $this;
	}

	protected function validTime($h, $m) {
		return $this->scope === self::SCOPE_DATE || ($h !== NULL && $m !== NULL) && (($h > -1 && $h < 24) && ($m > -1 && $m < 60));
	}

	protected function validDate($year, $month, $day) {
		return $this->scope === self::SCOPE_TIME || checkdate((int)$month, (int)$day, (int)$year);
	}

	protected function validDateTime($year, $month, $day, $hour, $minute) {
		return $this->validDate($year, $month, $day) && $this->validTime($hour, $minute);
	}

	protected function getSelectedValue() {
		if($this->selectedValue !== NULL) {
			return Nette\Utils\DateTime::from($this->selectedValue);
		} else {
			return $this->getValue();
		}
	}

	private function lz($val) {
		return strlen($val) === 2 ? $val : '0' . ($val === NULL ? '0' : $val);
	}

	public function getValue() {
		if($this->isNull()) {
			return NULL;
		}
		return ($this->validDate($this->year, $this->month, $this->day) && $this->validTime($this->hour, $this->minute)
			? Nette\Utils\DateTime::from($this->year . '-' . $this->lz($this->month) . '-' . $this->lz($this->day) . ' ' . $this->lz($this->hour) . ':' . $this->lz($this->minute) . ':00')
			: NULL);
	}

	public function isNull() {
		if($this->canBeNull) {
			return $this->setNull || ($this->year === NULL && $this->month === NULL && $this->day === NULL && $this->hour === NULL && $this->minute === NULL);
		}
		return FALSE;
	}

	public function loadHttpData() {
		$this->year = $this->getHttpData(Form::DATA_LINE, '-year');
		$this->month = $this->getHttpData(Form::DATA_LINE, '-month');
		$this->day = $this->getHttpData(Form::DATA_LINE, '-day');
		$this->hour = $this->getHttpData(Form::DATA_LINE, '-hour');
		$this->minute = $this->getHttpData(Form::DATA_LINE, '-minute');
		$this->selectedValue = NULL;
		$this->setNull = FALSE;
		if($this->canBeNull && $this->getHttpData(Form::DATA_LINE, '-null')==='1') {
			$this->setNull = TRUE;
		}
	}

	public function beforeRender() {
		$t = $this->getTemplate();
		$t->scope = $this->scope;
		$t->selectedValue = $this->getSelectedValue();
		$t->name = $this->name;
		$t->input = $this;
		$t->htmlName = $this->getHtmlName();
		$t->htmlId = $this->getHtmlId();
		$t->canBeNull = $this->canBeNull;
		$t->isNull = $this->isNull();
		$t->rich = $this->rich;
	}

	public function handleSelectDate($date) {
		$this->setValue($date);
		$this->redrawMeOnly();
	}

	public static function validateFilled(Nette\Forms\IControl $control) {
		return $control->day !== NULL || $control->month !== NULL || $control->year !== NULL || $control->hour !== NULL || $control->minute !== NULL;
	}

	public static function validateData(Nette\Forms\IControl $control) {
		return $control->validDate($control->year, $control->month, $control->day) && $control->validTime($control->hour, $control->minute);
	}

	public function viewTime() {

	}
	
	public function viewDate() {
	    
	}

	public function loadState(array $params) {
		parent::loadState($params);

		if(isset($params['selectedValue'])) {
			$this->setValue(Nette\Utils\DateTime::from($params['selectedValue']));
		}
		if(isset($params['setNull'])) {
			$this->setNull = (bool)$params['setNull'];
		}
	}

}