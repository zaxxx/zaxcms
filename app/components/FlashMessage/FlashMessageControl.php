<?php

namespace ZaxCMS\Components\FlashMessage;
use Nette,
	Zax,
	Zax\Application\UI\Control;

class FlashMessageControl extends Control {

	/**
	 * @var
	 */
	protected $flashes;

	/**
	 * @var bool
	 */
	protected $glyphicons = FALSE;

	public function setFlashes($flashes) {
		$this->flashes = $flashes;
		return $this;
	}

	/**
	 * @return $this
	 */
	public function enableGlyphicons() {
		$this->glyphicons = TRUE;
		return $this;
	}

	public function viewDefault() {
		$this->template->flashes = $this->flashes;
		$this->template->glyphicons = $this->glyphicons;
	}

	public function beforeRender() {

	}

}