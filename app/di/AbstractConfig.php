<?php

namespace ZaxCMS\DI;
use Zax,
	Nette,
	ZaxCMS;

abstract class AbstractConfig extends Nette\Object {

	protected $config;

	public function __construct(array $config) {
		$this->config = $config;
	}

}