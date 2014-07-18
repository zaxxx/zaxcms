<?php

namespace Zax\Latte;
use Nette,
	Kdyby,
    Zax;

/**
 * Class Helpers
 *
 * @package Zax\Latte
 */
class Helpers extends Nette\Object {

	/** @var Kdyby\Translation\Translator */
	protected $translator;

	/**
	 * @param Kdyby\Translation\Translator $translator
	 */
	public function setTranslator(Kdyby\Translation\Translator $translator) {
		$this->translator = $translator;
	}

	/**
	 * @var array
	 */
	protected static $days = [
		'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'
	];

	/**
	 * @var array
	 */
	protected static $months = [
		'january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'
	];

	/**
	 * @param $date
	 * @return string
	 */
	public function beautifulDate($date) {
        if(is_int($date)) {
            $date = Nette\Utils\DateTime::from($date);
        }
        return $this->translator->translate('latteHelpers.day.' . self::$days[$date->format('N')])
                . ' ' . $date->format('j')
                . '. ' . $this->translator->translate('latteHelpers.month.' . self::$months[$date->format('n')])
                . ' ' . $date->format('Y');
    }

	/**
	 * @param $date
	 * @return string
	 */
	public function beautifulTime($date) {
        if(is_int($date)) {
            $date = Nette\Utils\DateTime::from($date);
        }
        return $date->format('H:i');
    }

	/**
	 * @param $date
	 * @return string
	 */
	public function beautifulDateTime($date) {
        if(is_int($date)) {
            $date = Nette\Utils\DateTime::from($date);
        }
        return $this->beautifulDate($date) . ' - ' . $this->beautifulTime($date);
    }

	/**
	 * @param $date
	 * @return string
	 */
	public function relativeDate($date) {
        if(is_int($date)) {
            $date = Nette\Utils\DateTime::from($date);
        }
        $diff = $date->diff(new Nette\Utils\DateTime);
        if($diff->y > 0) {
	        return $this->translator->translate('latteHelpers.relativeDate.xYearsAgo', min(2, $diff->y), ['years' => $diff->y]);
        }
        if($diff->m > 0) {
	        return $this->translator->translate('latteHelpers.relativeDate.xMonthsAgo', min(2, $diff->m), ['months' => $diff->m]);
        }
        if($diff->d > 0) {
	        return $this->translator->translate('latteHelpers.relativeDate.xDaysAgo', min(2, $diff->d), ['days' => $diff->d]);
        }
        if($diff->h > 0) {
	        return $this->translator->translate('latteHelpers.relativeDate.xHoursAgo', min(2, $diff->h), ['hours' => $diff->h]);
        }
        if($diff->i > 0) {
	        return $this->translator->translate('latteHelpers.relativeDate.xMinutesAgo', min(2, $diff->i), ['minutes' => $diff->i]);
        }
        return $this->translator->translate('latteHelpers.relativeDate.fewSecondsAgo');
    }

	/**
	 * @param $helper
	 * @return mixed|null
	 */
	public function loader($helper) {
        if(method_exists($this, $helper)) {
            return call_user_func_array([$this, $helper], array_slice(func_get_args(), 1));
        }
        return NULL;
    }
    
}