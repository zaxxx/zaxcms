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
 * Class DateInput
 *
 * TODO: Form controls are outdated
 *
 * @package Zax\Forms\Controls
 */
class DateInput extends BaseControl {

	protected $day, $month, $year;

	/**
	 * @var bool
	 */
	protected $bootstrap = FALSE;

	/**
	 * @var array
	 */
	protected $months = array(
		'' => 'měsíc',
		1 => 'Leden',
		2 => 'Únor',
		3 => 'Březen',
		4 => 'Duben',
		5 => 'Květen',
		6 => 'Červen',
		7 => 'Červenec',
		8 => 'Srpen',
		9 => 'Září',
		10 => 'Říjen',
		11 => 'Listopad',
		12 => 'Prosinec'
	);

	public function __construct($label = NULL) {
		parent::__construct($label);
		$this->addCondition(array($this, 'validateFilled'))
			->addRule(array($this, 'validateData'), 'Toto není platný časový údaj.');
	}

	public function setValue($value) {
		if($value) {
			$date = Nette\Utils\DateTime::from($value);
			$this->day = $date->format('j');
			$this->month = $date->format('n');
			$this->year = $date->format('Y');
		} else {
			$this->day = $this->month = $this->year = NULL;
		}
		return $this;
	}

	public function getValue() {
		return checkdate((int)$this->month, (int)$this->day, (int)$this->year)
			? Nette\Utils\DateTime::from($this->year . '-' . $this->month . '-' . $this->day)
			: NULL;
	}

	public function loadHttpData() {
		$this->day = $this->getHttpData(Form::DATA_LINE, '[day]');
		$this->month = $this->getHttpData(Form::DATA_LINE, '[month]');
		$this->year = $this->getHttpData(Form::DATA_LINE, '[year]');
	}

	public function enableBootstrap() {
		$this->bootstrap = TRUE;
		return $this;
	}

	public function getControl() {
		$this->setOption('rendered', TRUE);

		$name = $this->getHtmlName();

		$day = Html::el('input')->type('number')->title('Den')->placeholder('den')->min(1)->max(31)->name($name . '[day]')->value($this->day);
		$month = Helpers::createSelectBox(
				$this->months,
				array('selected?' => ($this->month === NULL ? '' : $this->month)))->name($name . '[month]')->title('Měsíc');
		$year = Html::el('input')->type('number')->min(0)->max(3000)->title('Rok')->placeholder('rok')->name($name . '[year]')->value($this->year);
		if($this->bootstrap) {
			$day->addClass('form-control');
			$month->addClass('form-control');
			$year->addClass('form-control');
		}
		return Html::el('div')->class('date_input')->setHtml(
				$day . $month . $year);
	}

	public static function validateFilled(Nette\Forms\IControl $control) {
		return $control->day != NULL || $control->month != NULL || $control->year != NULL;
	}

	public static function validateData(Nette\Forms\IControl $control) {
		return checkdate((int)$control->month, (int)$control->day, (int)$control->year);
	}

}