<?php

namespace ZaxCMS\Components\FileManager;
use Nette,
	Zax;

class InvalidPathException extends Nette\Application\ForbiddenRequestException {}

class RootNotSetException extends Nette\InvalidStateException {}