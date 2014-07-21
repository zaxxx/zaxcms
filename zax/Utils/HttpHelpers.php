<?php

namespace Zax\Utils;
use Nette,
	Zax;

/**
 * Class HttpHelpers
 *
 * @package Zax\Utils
 */
class HttpHelpers extends Nette\Object {

	/**
	 * @var int
	 */
	static protected $maxUploadSize;

	/**
	 * @return int (in MB)
	 */
	static public function getMaxUploadSize() {
		if(self::$maxUploadSize === NULL) {
			$max_upload = (int)(ini_get('upload_max_filesize'));
			$max_post = (int)(ini_get('post_max_size'));
			$memory_limit = (int)(ini_get('memory_limit'));
			self::$maxUploadSize = min($max_upload, $max_post, $memory_limit);
		}
		return self::$maxUploadSize;
	}

}