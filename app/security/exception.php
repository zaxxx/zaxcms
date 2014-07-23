<?php

namespace ZaxCMS\Security;
use Nette,
	Zax;

class AuthenticationException extends Nette\Security\AuthenticationException {}

class UserLoginDisabledException extends AuthenticationException {}

class InvalidCredentialsException extends AuthenticationException {}

class InvalidNameException extends InvalidCredentialsException {}

class InvalidEmailException extends InvalidCredentialsException {}

class InvalidPasswordException extends InvalidCredentialsException {}

class UnverifiedUserException extends AuthenticationException {}

class BannedUserException extends AuthenticationException {}