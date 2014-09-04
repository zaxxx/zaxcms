<?php

namespace Zax\Model;
use Nette,
	Zax;

class LocaleNotSetException extends Nette\InvalidStateException {}

class CMSInstalledException extends Nette\Application\ForbiddenRequestException {}

class MissingEntityNameException extends Nette\InvalidStateException {}