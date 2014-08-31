<?php

namespace Zax\Texy;
use Zax,
	Nette;

class Youtube extends Nette\Object {

	public static function imageHandler($invocation, $image, $link) {
		$parts = explode(':', $image->URL);
		if (count($parts) !== 2) return $invocation->proceed();

		switch ($parts[0]) {
			case 'youtube':
				$video = htmlSpecialChars($parts[1]);
				$dimensions = 'width="'.($image->width ? $image->width : 425).'" height="'.($image->height ? $image->height : 350).'"';
				$code = '<div><object '.$dimensions.'>'
					. '<param name="movie" value="http://www.youtube.com/v/'.$video.'" /><param name="wmode" value="transparent" />'
					. '<embed src="http://www.youtube.com/v/'.$video.'" type="application/x-shockwave-flash" wmode="transparent" '.$dimensions.' /></object></div>';

				$texy = $invocation->getTexy();
				return $texy->protect($code, \Texy::CONTENT_BLOCK);
		}

		return $invocation->proceed();
	}

}