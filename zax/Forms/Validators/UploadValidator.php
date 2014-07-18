<?php

namespace Zax\Forms\Validators;
use Nette,
    Zax;

/**
 * Class UploadValidator
 *
 * @package Zax\Forms\Validators
 */
class UploadValidator extends Nette\Object {

	/**
	 * @param Nette\Forms\Controls\UploadControl $control
	 * @param                                    $extensions
	 * @return bool
	 */
	public static function validateExtension(Nette\Forms\Controls\UploadControl $control, $extensions) {
        $extensions = is_array($extensions) ? $extensions : explode(',', $extensions);
        foreach(static::toArray($control->getValue()) as $file) {
            /** @var Nette\Http\FileUpload $file */
            $extension = strtolower(pathinfo($file->getSanitizedName(), PATHINFO_EXTENSION));
            if(!in_array($extension, $extensions)) {
                return FALSE;
            }
        }
        return TRUE;
    }

	/**
	 * @param $value
	 * @return array
	 */
	private static function toArray($value)
    {
        return $value instanceof Nette\Http\FileUpload ? array($value) : (array) $value;
    }

}