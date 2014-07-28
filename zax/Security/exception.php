<?php

namespace Zax\Security;
use Zax,
	Nette;

class ForbiddenRequestException extends Nette\Application\ForbiddenRequestException {}

class PermissionNotSetException extends Nette\InvalidStateException {}