<?php

namespace Zax\Model;
use Nette,
	Zax;

class LocaleNotSetException extends Nette\InvalidStateException {}

class CMSInstalledException extends Nette\Application\ForbiddenRequestException {}

class MissingEntityClassNameException extends Nette\InvalidStateException {}